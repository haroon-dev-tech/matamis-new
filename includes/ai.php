<?php

function get_ai_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $path = __DIR__ . '/../config/ai.php';
    if (!file_exists($path)) {
        $path = __DIR__ . '/../config/ai.example.php';
    }

    $config = file_exists($path) ? require $path : [];
    if (!is_array($config)) {
        $config = [];
    }

    $config += [
        'enabled' => false,
        'provider' => 'groq',
        'api_key' => '',
        'model' => 'llama-3.1-8b-instant',
        'fallback_models' => [],
        'max_tokens' => 800,
        'requests_per_hour' => 30,
    ];

    return $config;
}

function ai_provider(): string
{
    $config = get_ai_config();
    return strtolower((string) ($config['provider'] ?? 'groq'));
}

function is_ai_enabled(): bool
{
    $config = get_ai_config();
    return !empty($config['enabled']) && trim((string) $config['api_key']) !== '';
}

function ai_rate_limit_ok(): bool
{
    $config = get_ai_config();
    $max = (int) ($config['requests_per_hour'] ?? 30);
    $key = 'ai_request_log';
    $now = time();
    $window = 3600;

    if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    $_SESSION[$key] = array_values(array_filter($_SESSION[$key], static function ($ts) use ($now, $window) {
        return ($now - (int) $ts) < $window;
    }));

    if (count($_SESSION[$key]) >= $max) {
        return false;
    }

    $_SESSION[$key][] = $now;
    return true;
}

function humanize_ai_error(string $message): string
{
    if (stripos($message, 'denied access') !== false) {
        return 'Your Google AI project was denied access. Use Groq instead: set provider to "groq" in config/ai.php '
            . 'and get a free key at https://console.groq.com/keys';
    }

    if (strpos($message, 'gemini-2.0-flash') !== false && strpos($message, 'limit: 0') !== false) {
        return 'Model gemini-2.0-flash is no longer available. Use gemini-2.5-flash or switch to Groq.';
    }

    if (strpos($message, 'Quota exceeded') !== false || strpos($message, 'RESOURCE_EXHAUSTED') !== false) {
        return 'API quota reached. Wait and try again, or switch provider in config/ai.php (e.g. groq).';
    }

    if (stripos($message, 'rate limit') !== false || stripos($message, '429') !== false) {
        return 'API rate limit reached. Please wait a minute and try again.';
    }

    if (stripos($message, 'invalid api key') !== false || stripos($message, 'incorrect api key') !== false) {
        return 'Invalid API key. Check config/ai.php and ensure the key matches your selected provider.';
    }

    return $message;
}

function parse_ai_json_content(string $content): array
{
    $parsed = json_decode($content, true);
    if (is_array($parsed)) {
        return $parsed;
    }

    if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
        $parsed = json_decode($matches[0], true);
        if (is_array($parsed)) {
            return $parsed;
        }
    }

    throw new RuntimeException('AI returned invalid JSON.');
}

function openai_compatible_chat_json(
    string $endpoint,
    string $apiKey,
    string $model,
    string $systemPrompt,
    string $userPrompt
): array {
    $config = get_ai_config();

    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ],
        'temperature' => 0.4,
        'max_tokens' => (int) $config['max_tokens'],
        'response_format' => ['type' => 'json_object'],
    ];

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
    ]);

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        throw new RuntimeException('Could not reach AI API: ' . ($curlError ?: 'Unknown error'));
    }

    $data = json_decode($response, true);
    if ($httpCode >= 400) {
        $message = $data['error']['message'] ?? ('AI request failed (HTTP ' . $httpCode . ')');
        throw new RuntimeException($message);
    }

    $content = $data['choices'][0]['message']['content'] ?? '';
    if ($content === '') {
        throw new RuntimeException('AI returned an empty response.');
    }

    return parse_ai_json_content($content);
}

function groq_chat_json(string $systemPrompt, string $userPrompt): array
{
    $config = get_ai_config();
    $apiKey = trim((string) $config['api_key']);
    if ($apiKey === '') {
        throw new RuntimeException('Groq API key is missing. Get a free key at https://console.groq.com/keys');
    }

    return openai_compatible_chat_json(
        'https://api.groq.com/openai/v1/chat/completions',
        $apiKey,
        (string) $config['model'],
        $systemPrompt,
        $userPrompt
    );
}

function gemini_models_to_try(): array
{
    $config = get_ai_config();
    $models = [];
    if (!empty($config['model'])) {
        $models[] = (string) $config['model'];
    }
    foreach ($config['fallback_models'] ?? [] as $model) {
        $models[] = (string) $model;
    }

    return array_values(array_unique(array_filter($models)));
}

function gemini_is_retryable_error(string $message): bool
{
    return strpos($message, 'Quota exceeded') !== false
        || strpos($message, 'RESOURCE_EXHAUSTED') !== false
        || strpos($message, 'limit: 0') !== false;
}

function gemini_generate_content(string $model, string $systemPrompt, string $userPrompt): array
{
    $config = get_ai_config();
    $encodedModel = rawurlencode($model);
    $apiKey = rawurlencode((string) $config['api_key']);
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $encodedModel . ':generateContent?key=' . $apiKey;

    $payload = [
        'systemInstruction' => [
            'parts' => [['text' => $systemPrompt]],
        ],
        'contents' => [
            [
                'role' => 'user',
                'parts' => [['text' => $userPrompt]],
            ],
        ],
        'generationConfig' => [
            'temperature' => 0.4,
            'maxOutputTokens' => (int) $config['max_tokens'],
            'responseMimeType' => 'application/json',
        ],
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
    ]);

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        throw new RuntimeException('Could not reach Google Gemini: ' . ($curlError ?: 'Unknown error'));
    }

    $data = json_decode($response, true);
    if ($httpCode >= 400) {
        $message = $data['error']['message'] ?? ('Gemini request failed (HTTP ' . $httpCode . ')');
        throw new RuntimeException($message);
    }

    $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    if ($content === '') {
        throw new RuntimeException('Gemini returned an empty response.');
    }

    return parse_ai_json_content($content);
}

function gemini_chat_json(string $systemPrompt, string $userPrompt): array
{
    $models = gemini_models_to_try();
    if (empty($models)) {
        throw new RuntimeException('No Gemini model configured in config/ai.php.');
    }

    $lastError = null;
    foreach ($models as $model) {
        try {
            return gemini_generate_content($model, $systemPrompt, $userPrompt);
        } catch (RuntimeException $e) {
            $lastError = $e;
            if (!gemini_is_retryable_error($e->getMessage())) {
                throw $e;
            }
        }
    }

    throw new RuntimeException($lastError ? $lastError->getMessage() : 'Gemini request failed.');
}

function ai_chat_json(string $systemPrompt, string $userPrompt): array
{
    if (!is_ai_enabled()) {
        throw new RuntimeException('AI is not configured. Add your API key in config/ai.php.');
    }

    if (!function_exists('curl_init')) {
        throw new RuntimeException('PHP cURL extension is required for AI integration.');
    }

    try {
        $provider = ai_provider();
        if ($provider === 'groq') {
            return groq_chat_json($systemPrompt, $userPrompt);
        }
        if ($provider === 'gemini') {
            return gemini_chat_json($systemPrompt, $userPrompt);
        }

        throw new RuntimeException('Unknown AI provider "' . $provider . '". Use groq or gemini in config/ai.php.');
    } catch (RuntimeException $e) {
        throw new RuntimeException(humanize_ai_error($e->getMessage()));
    }
}

function generate_observation_risk_recommendations(
    string $head,
    string $details,
    ?string $companyName = null
): array {
    $systemPrompt = 'You are a senior financial and management consultant preparing observations for a UAE company audit/MIS report. '
        . 'Given an observation heading and details, produce concise professional Risk and Recommendations sections. '
        . 'Use bullet points (with "• " prefix) where helpful. Keep each section 2–5 bullets or short paragraphs. '
        . 'Return valid JSON only with exactly these keys: "risk" (string) and "recommendations" (string).';

    $userPrompt = "Company: " . ($companyName ?: 'Not specified') . "\n\n"
        . "Observation Head:\n" . $head . "\n\n"
        . "Details:\n" . $details;

    $result = ai_chat_json($systemPrompt, $userPrompt);

    $risk = trim((string) ($result['risk'] ?? ''));
    $recommendations = trim((string) ($result['recommendations'] ?? ''));

    if ($risk === '' || $recommendations === '') {
        throw new RuntimeException('AI response was missing risk or recommendations.');
    }

    return [
        'risk' => $risk,
        'recommendations' => $recommendations,
    ];
}

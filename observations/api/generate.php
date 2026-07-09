<?php

header('Content-Type: application/json; charset=utf-8');

$requireAuth = true;
require __DIR__ . '/../../includes/bootstrap.php';
require __DIR__ . '/../../includes/ai.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

if (!verify_csrf()) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid security token. Please refresh the page and try again.']);
    exit;
}

$head = trim($_POST['head'] ?? '');
$details = trim($_POST['details'] ?? '');
$companyName = trim($_POST['company_name'] ?? '');

if ($head === '') {
    http_response_code(422);
    echo json_encode(['error' => 'Head is required before generating risk and recommendations.']);
    exit;
}

if ($details === '') {
    http_response_code(422);
    echo json_encode(['error' => 'Details are required for AI generation.']);
    exit;
}

if (!is_ai_enabled()) {
    http_response_code(503);
    echo json_encode(['error' => 'AI is not configured. Check config/ai.php.']);
    exit;
}

if (!ai_rate_limit_ok()) {
    http_response_code(429);
    echo json_encode(['error' => 'Too many AI requests. Please wait a few minutes and try again.']);
    exit;
}

try {
    $result = generate_observation_risk_recommendations($head, $details, $companyName ?: null);
    echo json_encode([
        'success' => true,
        'risk' => $result['risk'],
        'recommendations' => $result['recommendations'],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage() ?: 'AI generation failed. Please try again.']);
}

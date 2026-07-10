<?php

/**
 * AI configuration — cloud API only (no local install).
 *
 * Provider: groq (recommended, free tier)
 * Get a free API key: https://console.groq.com/keys
 *
 * Alternative: set provider to 'gemini' and use a Google AI Studio key.
 */
return [
    'enabled' => true,
    'provider' => 'groq',
    'api_key' => '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',
    'model' => 'llama-3.1-8b-instant',
    'max_tokens' => 800,
    'requests_per_hour' => 30,
];
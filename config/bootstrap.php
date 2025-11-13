<?php
/**
 * Bootstrap file - loads environment variables from .env
 * This file should be included FIRST in index.php
 */

// Load .env file if it exists
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\\1$/', $value, $matches)) {
                $value = $matches[2];
            }
            
            // Set environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
    error_log("[BOOTSTRAP] Loaded " . count($lines) . " lines from .env");
} else {
    error_log("[BOOTSTRAP] WARNING: .env file not found at: $envFile");
}

// Log loaded DB config for debugging
if (getenv('DB_HOST')) {
    error_log("[BOOTSTRAP] DB_HOST loaded: " . getenv('DB_HOST'));
} else {
    error_log("[BOOTSTRAP] WARNING: DB_HOST not loaded from .env");
}

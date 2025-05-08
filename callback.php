<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/classes/base.php';
require_once CLASSES_PATH . 'Ub/Callback/Callback.php';

try {
    $callback = new UbCallbackCallback();
    $callback->run();
} catch (Throwable $e) {
    error_log($e->getMessage());

    if (ini_get('display_errors')) {
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    } else {
        http_response_code(500);
    }
}

<?php
declare(strict_types=1);

$config = require __DIR__ . '/../config.php';

date_default_timezone_set($config['app']['timezone'] ?? 'UTC');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';

$pdo = wl_db($config);


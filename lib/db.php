<?php
declare(strict_types=1);

function wl_db(array $config): PDO
{
    $db = $config['db'] ?? [];

    $host = $db['host'] ?? '127.0.0.1';
    $name = $db['name'] ?? 'weightlanka';
    $user = $db['user'] ?? 'root';
    $pass = $db['pass'] ?? '';
    $charset = $db['charset'] ?? 'utf8mb4';

    $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo "<h2>Database connection failed</h2>";
        echo "<p>Please import <code>db/weightlanka.sql</code> and check <code>config.php</code>.</p>";
        echo "<pre style='white-space:pre-wrap;background:#111;color:#eee;padding:12px;border-radius:8px;'>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</pre>";
        exit;
    }
}


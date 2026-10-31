<?php

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log('MiniShop database connection failed: ' . $exception->getMessage());
    http_response_code(500);
    exit(APP_DEBUG
        ? 'Không thể kết nối cơ sở dữ liệu. Hãy kiểm tra cấu hình và import database/minishop.sql.'
        : 'Hệ thống đang bận, vui lòng thử lại sau.');
}

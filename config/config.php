<?php

define('APP_NAME', 'MiniShop');
define('APP_DEBUG', getenv('MINISHOP_DEBUG') !== '0');
$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/minishop/index.php'));
if (basename($scriptDirectory) === 'admin') {
    $scriptDirectory = str_replace('\\', '/', dirname($scriptDirectory));
}
$defaultBaseUrl = $scriptDirectory === '/' ? '/' : $scriptDirectory . '/';
define('BASE_URL', rtrim(getenv('MINISHOP_BASE_URL') ?: $defaultBaseUrl, '/') . '/');

define('DB_HOST', getenv('MINISHOP_DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('MINISHOP_DB_PORT') ?: '3307');
define('DB_NAME', getenv('MINISHOP_DB_NAME') ?: 'minishop');
define('DB_USER', getenv('MINISHOP_DB_USER') ?: 'root');
define('DB_PASS', getenv('MINISHOP_DB_PASS') ?: '');

<?php

require_once __DIR__ . '/../config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/db.php';

$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');

<?php
define('ENV_PRODUCTION', false);
define('APP_HOST', 'fripple.android.com');
define('APP_BASE_PATH', '/');
define('APP_URL', 'http://fripple.android.com/');

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
ini_set('error_log', LOGS_DIR.'php.log');
ini_set('session.auto_start', 0);

// MySQL: board
define('DB_DSN', 'mysql:host=localhost;dbname=u760867219_gk');
define('DB_USERNAME', 'u760867219_gk');
define('DB_PASSWORD', '123456');
define('DB_ATTR_TIMEOUT', 3);

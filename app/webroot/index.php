<?php
define('ROOT_DIR', dirname(dirname(__DIR__)).'/');
define('APP_DIR', ROOT_DIR.'app/');
define('IMG_DIR', APP_DIR.'webroot/bootstrap/img/');
require_once ROOT_DIR.'dietcake/dietcake.php';
require_once CONFIG_DIR.'bootstrap.php';
require_once CONFIG_DIR.'core_development.php';
Dispatcher::invoke();

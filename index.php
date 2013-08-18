<?php
define('WEB_BASE_PATH', './');
define('LIB_BASE_PATH', 'libraries/');
define('CONFIG_BASE_PATH', 'config/');
define('DB_CONFIG_BASE_PATH', 'config/');

require_once(WEB_BASE_PATH . 'libraries/mvc/Controller.class.inc.php');
require_once(WEB_BASE_PATH . 'libraries/mvc/Model.class.inc.php');

$control = new Controller();
$control->run();
?>

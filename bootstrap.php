<?php
// Base directory paths 
define('FRAMEWORK_DIR', realpath(dirname(__FILE__)));
define('CONFIG_PATH', FRAMEWORK_DIR . '/config/');
define('SCRIPT_PATH', FRAMEWORK_DIR . '/script/');
define('LOG_PATH', FRAMEWORK_DIR . '/log/');


// Base paths for the classes called by the autoloader
$basePaths = array('/lib/','/script/');
foreach($basePaths as $basePath) {
    $path = get_include_path() . PATH_SEPARATOR . FRAMEWORK_DIR . $basePath;
    set_include_path($path);
}

// Autoloader registration
include_once('autoloader.php');

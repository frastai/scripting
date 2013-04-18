<?php

// Command line access validation
if (isset($_SERVER['HTTP_HOST']) || php_sapi_name() != 'cli') {
    die('Access Denied');
}

// Boostrap, absolute path inclusion
include_once(realpath(dirname(__FILE__)) . '/bootstrap.php');

// Cli interface utilities
$cli = new Util_CliCommand();

// Environment sensitive settings inclusion: --env=<environment>
if (isset($cli->arguments['env']) && !empty($cli->arguments['env']) && file_exists(CONFIG_PATH . strtolower($cli->arguments['env']) . '.php')) {
    include_once (CONFIG_PATH . $cli->arguments['env'] . '.php');
} else {
    $cli->abort("Error: Configuration file not found.");
}

// Script name request by argument: --script=<name>
if (isset($cli->arguments['script']) && !empty($cli->arguments['script']) && file_exists(SCRIPT_PATH . strtolower($cli->arguments['script']) . '.php')) {
    $scriptName = $cli->arguments['script'];
} else {
    $cli->abort("Error: Script not found.");
}

// Command line setup
$cli->setPassword(CLI_PASSWORD)
        ->setLogfile(LOG_PATH, $scriptName);

// Run script (flags: --screenOutput --logFile)
try {
    $script = new $scriptName($cli);
    if (!is_a($script, 'Abstract_ScriptRunner')) {
        $cli->abort("Error: " . $scriptName . " must inherit from Abstract_ScriptRunner class");
    }
    $script->run()
            ->log()
            ->close();
} catch (Exception $ex) {
    $cli->abort("Error: " . $ex->getMessage());
}

<?php

# Settings #####################################################################
ini_set('display_errors', 1);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING & ~E_STRICT);
error_reporting(E_ALL);
//ini_set('memory_limit', '128M');
//set_time_limit(0);
//ignore_user_abort(1);
//ini_set('default_charset', 'UTF-8');
date_default_timezone_set('America/Argentina/Buenos_Aires');
################################################################################

# MySql Database ###############################################################
define('USER',          '');
define('PASSWORD',      '');
define('HOST',          '');
define('PORT',          3306);
define('DATABASE',      '');
define('CHARSET',       'UTF-8');
define('TABLE',         '');
################################################################################

# Script Password ##############################################################
define('CLI_PASSWORD',  'jaimito');
################################################################################
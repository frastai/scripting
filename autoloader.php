<?php

class AutoLoader {

	private static $classFileSuffix = '.php';
        private static $classNameSeparator = '_';
        
	public static function loadClass($className) {
            $file = str_replace(self::$classNameSeparator, DIRECTORY_SEPARATOR, strtolower($className) ) . self::$classFileSuffix;
            require_once($file);
	} 
   
}

spl_autoload_register(array('AutoLoader', 'loadClass'));

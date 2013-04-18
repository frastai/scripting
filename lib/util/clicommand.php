<?php

/*
 * @author Jaime Frastai at Globant
 */
class Util_CliCommand {

    const PROMPT_CHARACTER = '=>';
    const LINE_SEPARATOR = "\r\n";

    private $password = null;
    private $logFile = null;
    private $stdin = null;
    public $arguments = array();

    public function __construct() {
        $this->stdin = fopen('php://stdin', 'r');
        $this->arguments = $this->getArguments();
    }

    public function close() {
        fclose($this->stdin);
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setLogfile($dirPath, $fileName) {
        if (file_exists($dirPath)) {
            $this->logFile = $dirPath . DIRECTORY_SEPARATOR . strtolower($fileName);
        } else {
            $this->abort("Error: Could not set log file");
        }
        return $this;
    }

    public function abort($string = null) {
        echo (self::LINE_SEPARATOR . $string . self::LINE_SEPARATOR);
        exit;
    }

    public function getStringOuptut($string = '') {
        return self::LINE_SEPARATOR . '[' . date("Y-m-d H:i:s") . ']' . ' ' . $string;
    }

    public function writeLogFile($string) {
        $location = $this->logFile;
        $file = fopen($location, 'a');
        $caracteres = fwrite($file, $string, strlen($string));
        if ($caracteres === false) {
            $this->abort("Error: Unable to write in $location");
        }
        fclose($file);
        return $this;
    }

    // Bash inmput dialog
    public function getInput($prompt, $valid_inputs = array()) {
        if (empty($this->stdin)) {
            $this->abort('Error: cannot open cli dialog');
        }
        $input = '';
        if (empty($valid_inputs)) {
            echo self::LINE_SEPARATOR . $prompt . self::LINE_SEPARATOR . self::PROMPT_CHARACTER;
            return strtolower(trim(fgets($this->stdin)));
        } else {
            while (empty($input) || !in_array($input, $valid_inputs)) {
                echo self::LINE_SEPARATOR . $prompt . ' [' . implode('|', $valid_inputs) . "]" . self::LINE_SEPARATOR . self::PROMPT_CHARACTER;
                $input = strtolower(trim(fgets($this->stdin)));
            }
        }
        return $input;
    }

    public function checkPassword($question) {
        if (empty($this->password)) {
            return;
        }
        $input = '';
        while ($input != $this->password) {
            $input = $this->getInput($question);
        }
        return $this;
    }

    private function getArguments() {
        global $argv;
        array_shift($argv);
        $out = array();
        foreach ($argv as $arg) {
            if (substr($arg, 0, 2) == '--') {
                $eqPos = strpos($arg, '=');
                if ($eqPos === false) {
                    $key = substr($arg, 2);
                    $out[$key] = isset($out[$key]) ? $out[$key] : true;
                } else {
                    $key = substr($arg, 2, $eqPos - 2);
                    $out[$key] = substr($arg, $eqPos + 1);
                }
            } else if (substr($arg, 0, 1) == '-') {
                if (substr($arg, 2, 1) == '=') {
                    $key = substr($arg, 1, 1);
                    $out[$key] = substr($arg, 3);
                } else {
                    $chars = str_split(substr($arg, 1));
                    foreach ($chars as $char) {
                        $key = $char;
                        $out[$key] = isset($out[$key]) ? $out[$key] : true;
                    }
                }
            } else {
                $out[] = $arg;
            }
        }
        return $out;
    }

}

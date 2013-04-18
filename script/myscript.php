<?php

/**
 * @author Jaime Frastai at Globant
 */
class MyScript extends Abstract_ScriptRunner {

    public function __construct(Util_CliCommand $cli) {
        $cli->checkPassword('Enter your password:');
        parent::__construct($cli);
    }

    public function run() {
        $input = $this->cli->getInput("get data?", array('yes', 'no'));
        if ($input == 'yes') {
            $data = json_encode($this->parameters);
            $this->logString.= json_encode($data);
        } else {
            $this->logString.= 'no data';
        }
        return $this;
    }

}
<?php

/**
 * @author Jaime Frastai at Globant
 */
abstract class Abstract_ScriptRunner {

    protected $cli;
    protected $db;
    protected $logString;
    protected $parameters;
    protected $dryRun;
    protected $screenOutput;
    protected $logFile;

    public function __construct(Util_CliCommand $cli) {
        $this->cli = $cli;
        $this->parameters = $this->cli->arguments;
        $this->logString = $this->cli->getStringOuptut();
        $this->dry = (isset($this->parameters['dryRun'])) ? true : false;
        $this->screenOutput = (isset($this->parameters['screenOutput'])) ? true : false;
        $this->logFile = (isset($this->parameters['logFile'])) ? true : false;
    }

    abstract public function run();

    public function log() {
        if ($this->screenOutput) {
            echo $this->logString . Util_CliCommand::LINE_SEPARATOR;
        }
        if ($this->logFile) {
            $this->cli->writeLogFile($this->logString);
        }
        return $this;
    }

    public function close() {
        if (!empty($this->db)) {
            $this->db->disconnect();
        }
        $this->cli->close();
    }

}
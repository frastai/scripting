<?php

/**
 * @author Jaime Frastai at Globant
 */
class Database_MySqli implements Interface_DataRetriever {

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    public $link;
    private static $instance;

    private function __construct($server, $port, $user, $password, $database) {
        $this->server = $server;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    public static function getSingleton($server, $port, $user, $password, $database) {
        if (!isset(self::$instance)) {
            self::$instance = new self($server, $port, $user, $password, $database);
        }
        return self::$instance;
    }

    public function connect() {
        $this->link = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port );
        if ($this->link->connect_error) {
            throw new Exception('Connect Error (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
        }
    }

    public function disconnect() {
        $this->link->close();
        self::$instance = null;
    }

    public function getData($sql) {
        $result = $this->link->query($sql);
        if (!$result) {
            throw new Exception($this->link->error);
        }
        $max = $result->num_rows;
        $data = array();
        for ($i = 0; $i < $max; $i++) {
            $data[$i] = $result->fetch_assoc();
        }
        $result->close();
        return $data;
    }

    public function getRow($table, array $compositeKey, $field = '*') {
        $string = "SELECT $field FROM $table WHERE ";
        foreach ($compositeKey as $key => $value) {
            $string.= " $key = '$value' AND";
        }
        $string = substr($string, 0, strlen($string) - 3);
        $data = $this->getData($string);
        return $data[0];
    }

    public function setData($sql) {
        $result = $this->link->query($sql);
        if (!$result) {
            throw new Exception($this->link->error);
        }
        return true;
    }

    public function insertData($table, array $data) {
        $string = "INSERT INTO $table (";
        foreach ($data as $key => $value) {
            $string.= " $key, ";
        }
        $string = substr($string, 0, strlen($string) - 2);
        $string.=") VALUES (";
        foreach ($data as $key => $value) {
            if ($value == 'NOW()') {
                $string.= "$value, ";
            } else {
                $string.= "'$value', ";
            }
        }
        $string = substr($string, 0, strlen($string) - 2) . ")";
        return $this->setData($string);
    }

    public function updateData($table, array $data, $where) {
        $string = "UPDATE  $table SET ";
        foreach ($data as $key => $value) {
            $string.= " $key =";
            if ($value == 'NOW()') {
                $string.= "$value, ";
            } else {
                $string.= "'$value', ";
            }
        }
        $string = substr($string, 0, strlen($string) - 2) . " WHERE $where";
        return $this->setData($string);
    }

    public function getIncrementedValue() {
        return $this->link->insert_id;
    }

}
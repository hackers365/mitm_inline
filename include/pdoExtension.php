<?php

class pdoExtension {
    public static $instance = null;
    public $setting = array(
        'host' => '127.0.0.1',
        'dbname' => 'injection',
        'user' => 'injection',
        'password' => 'injection',
    );
    public static function getInstance() {
        if (self::$instance) {
            return self::$instance;
        }
        $dsn = "mysql:host={$this->setting['host']};dbname={$this->setting['dbname']}";
        try {
            self::$instance = new PDO($dsn, $this->setting['user'], $this->setting['password']);
        }
        catch (Exception $e){
            return null;
        }
    }

    public function static bindParams($db, $params) {
        foreach($params as $_k => $_v) {
            $db->bindParam($_k, $_v);
        }
    }

    public function static query($sql, $params) {
        $db = self::getInstance();
        $result = array();
        if ($db) {
            try {
                $db->prepare($sql);
                self::bindParams($db, $params);
                $db->execute();
                $result = $db->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (Exception $e){
                $result = array();
            }
        }
        return $result;
    }

    public function static insert() {

    }
}

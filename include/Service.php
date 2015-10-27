<?php
require 'include/pdoExtension.php';

class Service {
    public static $instance = null;

    public static function getInstance() {
        if (self::$instance) {
            return self::$instance;
        }
        $class_name = get_called_class();
        self::$instance = new $class_name;
        return self::$instance;
    }

    //管理员界面的api
    public function get_url($type, $task_id) {
        $prefix = 'http://127.0.0.1:8775';
        $url = '';
        if ($type == 'index') {
            $url = $prefix . '/admin/' . $task_id . '/list';
        } else if($type == 'list'){
            $url = $prefix . '/option/' . $task_id . '/list';
        } else {
            $url = $prefix . '/scan/' . $task_id . '/' . $type;
        }

        return $url;
    }

    public function getTaskList() {

    }

    public function getData($task_id, $type) {
        $url = $this->get_url($type, $task_id);
        return Util::request($url);
    }

    private function getUrlDigest($data) {

    }

    private function getUrlInfo($data) {
        $ret = array();
        $info = parse_url($url);

        $ret = array(
            'domain' => $info['host'],
            'url_digest' =>
        );
    }

    //扫描的结果入库
    public function scanResult($task_id) {
        //拿到扫描结果
        $data = $this->getData($task_id, 'data');

        $domain_info = $this->getUrlInfo($data);
    }

}

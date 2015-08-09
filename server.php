<?php
include "template.php";

$admin_id = 'e71b39b9089d97ed6d298d4b49c41d0d';

function request($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);

    $result = curl_exec($ch);
    curl_close($ch);
    if ($result) {
        $result = json_decode($result, true);
    }
    return $result ? $result : array();
}

//管理员界面的api
function get_url($type, $task_id) {
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

class adminServer {
    public $server;
    public $setting = array(
        'worker_num' => 5,
        'daemonize' => false,
    );

    public function run() {
        $this->server = new swoole_http_server('0.0.0.0', 9501);
        $this->server->on('request', array($this, 'onRequest'));
        $this->server->set($this->setting);
        //read admin_id
        global $admin_id;
        $admin_id = file_get_contents('/tmp/admin_id.txt');
        $this->server->start();
    }

    public function onRequest($request, $response) {
        $uri = $request->server['request_uri'];
        if ($uri == '/' || $uri == '/index') {
            //主页面
            $this->index($request, $response);
        } else{
            $action = '';
            $task_id = '';
            $pattern = '@^/api/([^/]+)/([^/]+)/?$@';
            if (preg_match($pattern, $uri, $match)) {
                $action = $match[2];
                $task_id = $match[1];
            }
            if (!$action) {
                $response->end('error');
                return;
            }

            $allow_action = array(
                'log' => '',
                'data' => '',
                'start' => '',
                'stop' => '',
                'list' => '',
            );
            if (!isset($allow_action[$action])) {
                $response->end('action error');
            }
            $this->action($action, $task_id, $request, $response);
        }
    }

    public function action($action, $task_id, $request, $response) {
        $url = get_url($action, $task_id);
        $response->end(json_encode(request($url)));
    }

    public function index($request, $response) {
        global $admin_id;
        $url = get_url('index', $admin_id);
        $result = request($url);
        $task_list = array();
        if (!empty($result['tasks'])) {
            $task_list = $result['tasks'];
        }

        $tpl = new template();
        $tpl->assign('task_list', $task_list);
        $str = $tpl->fetch('index');
        $response->end($str);
        //$response->end($tpl->fetch('index'));

    }
}

$server = new adminServer();
$server->run();

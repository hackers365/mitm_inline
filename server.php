<?php
include "template.php";
include "include/Util.php";
include "include/Service.php";

$admin_id = 'e71b39b9089d97ed6d298d4b49c41d0d';

class adminServer {
    public $server;
    public $is_tasking = false;
    public $setting = array(
        'worker_num' => 5,
        'daemonize' => false,
        'task_worker_num' => 5,
    );

    public function run() {
        $this->server = new swoole_http_server('0.0.0.0', 9501);
        $this->server->on('request', array($this, 'onRequest'));

        $this->server->on('workerstart', array($this, 'onWorkerStart'));
        $this->server->on('task', array($this, 'scanTask'));

        $this->server->set($this->setting);
        //read admin_id
        global $admin_id;
        $admin_id = file_get_contents('/tmp/admin_id.txt');

        //add timer
        //$this->addTimer();
        $this->server->start();
    }

    //每两秒钟扫描一次列表 ,发现有停止的就入库.
    public function scanTask($serv, $task_id, $from_id, $data) {
        global $admin_id;
        $task_list = get_url('list', $admin_id);
        foreach($task_list as $_task_id => $_task_info) {
            if (!empty($_task_info['stop'])) {
                //进库
                Service::getInstance()->scanResult($_task_id, $_task_info['url']);
            }
        }
    }

    //添加检测是否完成的定时器
    public function addTimer() {
        swoole_timer_tick(10000, function($timer_id, $params=null) {
            if ($this->is_tasking) {
                return;
            }
            $this->server->task();
            $this->is_tasking = true;
        });
    }

    public function onFinish($http, $task_id, $data) {
        $this->is_tasking = false;
    }

    public function onWorkerStart($serv, $worker_id) {
        if ($worker_id == 0) {
            $this->addTimer();
        }
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
            $task_pattern = '@^/task/([^/]+)/([^/]+)/?$@';
            if (preg_match($pattern, $uri, $match)) {
                $action = $match[2];
                $task_id = $match[1];

                $allow_action = array(
                    'log' => '',
                    'data' => '',
                    'start' => '',
                    'stop' => '',
                    'status' => '',
                    'list' => '',
                );
                if (!isset($allow_action[$action])) {
                    $response->end('action error');
                }
                $this->action($action, $task_id, $request, $response);

            } elseif(preg_match($task_pattern, $uri, $match)) {
                $action = $match[2];
                $task_id = $match[1];
                if (!$action) {
                    $response->end('error');
                    return;
                }
                $this->task($action, $task_id, $request, $response);
            }
        }
    }

    public function task($action, $task_id, $request, $response) {
        $url = get_url('log', $task_id);
        $request = Util::request($url);

        $tpl = new template();
        $log_list = array();
        if (!empty($request['log'])) {
            $log_list = $request['log'];
        }
        $tpl->assign('log_list', $log_list);
        $tpl->assign('task_id', $task_id);
        $str = $tpl->fetch('task');
        $response->end($str);
    }

    public function action($action, $task_id, $request, $response) {
        $url = get_url($action, $task_id);
        $response->end(json_encode(Util::request($url)));
    }

    public function index($request, $response) {
        global $admin_id;
        $url = get_url('index', $admin_id);
        $result = Util::request($url);
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

<?php
require 'redis.php';

$prefix = 'http://127.0.0.1:8775/';

function request($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);

    $result = curl_exec($ch);
    if ($result) {
        $result = json_decode($result, true);
    }
    return $result ? $result : array();
}

function task_list($admin_id) {
    $url = $prefix . '/admin/' . $admin_id . '/list';
    $result = request($url);
    if (!empty($result)) {

    }
}
$redis = new RedisExt();
$admin_id = $redis->get('admin_id');
if (!$admin_id) {
    echo '没有找到admin_id,是不是sqlmapapi.py程序没有启动?';
    exit;
}

$task_list = task_list($admin_id);

echo <<<EOF
<html>
    <head>
        <meta charset="utf-8" />
        <title>sqlmap注入管理系统</title>
    </head>

    <body>

    </body>
</html>
EOF;

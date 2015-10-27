
<html>
    <head>
        <meta charset="utf-8" />
        <title>sqlmap注入管理系统</title>
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
        <style>
            .popover {
                max-width: 800px;
                text-align: left;
            }
        </style>
    </head>

    <body>

        <div>
            <h1>
                sql注入管理系统
            </h1>
        <table class="table table-bordered table-hover table-responsive">
        <th>url</th>
        <th>状态</th>
        <th>操作</th>
        <?php
            foreach($task_list as $task_id => $task_info) {
                echo "<tr task_id=\"{$task_id}\">";
                    echo "<td class=\"_log\" class=\"\" style=\"max-width: 300px;word-wrap: break-word;\">
                            <a target=\"_blank\" href=\"/task/{$task_id}/log\">{$task_info['url']}</a>
                        </td>";
                    if (!empty($task_info['stop'])) {
                        $status = '已停止.';
                    } else {
                        $status = '正在运行.';
                    }
                    echo "<td style=\"width:200px;\">{$status}</td>";
                    echo "<td>
                        <!--
                            <button action=\"start\" class=\"_action btn btn-success\">启动</button>
                        -->
                        <button action=\"stop\" class=\"_action btn btn-info\">停止</button>
                        <!--
                            <button action=\"kill\" type=\"button\" class=\"_action btn btn-danger\">删除</button>
                        -->
                    </td>";
                echo '</tr>';
            }
        ?>
        </table>
        </div>

        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script>
            //~ function request(url, ) {
//~
            //~ }
            $(document)
            /*
            .on('click', 'td._log', function(e) {
                $.getJSON('/api/' + task_id + '/log', function(data) {
                    var str = '';
                    for(var i=0;i<data.log.length;i++) {
                        str = str + data.log[i]['time'] + ':' + data.log[i]['level'] + '    ' + data.log[i]['message'] + "<br>";
                    }
                });
            })
            */
            .on('click', '._action', function(e) {
                var $this = $(this),
                    task_id = $this.parents('tr[task_id]').attr('task_id'),
                    action = $this.attr('action');
                    url = '/api/' + task_id + '/' + action;
                $.getJSON(url, function(data) {
                    alert('操作成功.');
                });
            });

        </script>
    </body>
</html>

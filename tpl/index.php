
<html>
    <head>
        <meta charset="utf-8" />
        <title>sqlmap注入管理系统</title>
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
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
                echo "<tr class=\"_log\" task_id=\"{$task_id}\">";
                    echo "<td class=\"\" style=\"max-width: 300px;word-wrap: break-word;\">{$task_info['url']}</td>";
                    if (!empty($task_info['stop'])) {
                        $status = '正在运行.';
                    } else {
                        $status = '已停止.';
                    }
                    echo "<td style=\"width:200px;\">{$status}</td>";
                    echo "<td><button class=\"_stop btn btn-success\">启动</button><button class=\"_stop btn btn-info\">停止</button><button type=\"button\" class=\"_delete btn btn-danger\">删除</button></td>";
                echo '</tr>';
            }
        ?>
        </table>
        </div>

        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script>
            $(document)
            .on('click', 'tr._log', function(e) {
                var task_id = $(this).attr('task_id');
                $.getJSON('/api/' + task_id + '/log', function(data) {
                    console.log(data);
                });
            });

        </script>
    </body>
</html>

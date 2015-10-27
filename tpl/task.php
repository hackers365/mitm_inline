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
            <textarea class="form-control" rows="20" id="show_panel">

            </textarea>

        </div>

        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script>
            var task_id = "<?php echo $task_id;?>";
            setInterval(function() {
                $.getJSON('/api/' + task_id + '/log', function(data) {
                        var str = '';
                        for(var i=0;i<data.log.length;i++) {
                            str = str + data.log[i]['time'] + ':' + data.log[i]['level'] + '    ' + data.log[i]['message'] + "\n";
                        }
                        var $show_panel = $('#show_panel');
                        $show_panel.val(str);
                        $show_panel.scrollTop(10000);
                    });
                }, 2000);
/*
            .on('click', '._action', function(e) {
                var $this = $(this),
                    task_id = $this.parents('tr[task_id]').attr('task_id'),
                    action = $this.attr('action');
                    url = '/api/' + task_id + '/' + action;
                $.getJSON(url, function(data) {
                    alert('操作成功.');
                });
            });
*/
        </script>
    </body>
</html>

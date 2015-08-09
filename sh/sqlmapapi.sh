#!/bin/bash
pid=`ps aux|grep sqlmapapi.py|grep -v grep|awk -F' ' '{print $2}'`
kill -9 $pid >/dev/null 2>&1
cd /data/software/sqlmap/
/data/software/sqlmap/sqlmapapi.py -s > /tmp/sqlmap_output.txt &
sleep 3s
admin_id=`grep -i 'admin id' /tmp/sqlmap_output.txt|awk -F' ' '{print $NF}'`
if [ -n $admin_id ];then
    #/usr/local/redis/redis-cli set 'admin_id' $admin_id
    echo -n $admin_id > /tmp/admin_id.txt
    echo $admin_id
else
    echo 'fail'
fi


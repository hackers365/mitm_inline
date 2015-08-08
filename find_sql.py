import hashlib
import re
import redis
import json
import urllib2
import urllib
'''
def request(context, flow):
    query_dict = flow.request.get_query()
    method = flow.request.method.lower()
    print flow.request.headers
    if method == 'get' and query_dict:
        print flow.request.url
    #print(flow.request.headers)
    #flow.response.headers["newheader"] = ["foo"]
'''

def md5(url):
    m = hashlib.md5()
    m.update(url)
    return m.hexdigest()

def allow_type(content_type):
    allow_list = ['text/html', 'text/plain', 'application/json', 'application/xml']
    for i in allow_list:
        if re.search(i, content_type):
            return True
    return False

def get_url_path(url):
    return url.split('?')[0]

def send_sqlmap(flow, method='get'):
    options = {}
    url = get_url_path(flow.request.url)
    key = md5(method + url)
    if url_exist(key):
        print url + ' exists'
        return False
    if flow.request.headers['Cookie'] and flow.request.headers['Cookie'][0]:
        options['cookie'] = flow.request.headers['Cookie'][0]
    if flow.request.headers['User-Agent'] and flow.request.headers['User-Agent'][0]:
        options['user-agent'] = flow.request.headers['User-Agent'][0]
    if flow.request.headers['Referer'] and flow.request.headers['Referer'][0]:
        options['referer'] = flow.request.headers['Referer'][0]
    if method.lower() == 'get':
        task_new(flow.request.url, options)
        r = redis.Redis('127.0.0.1')
        r.set(key, '1')
    elif method.lower() == 'post':
        options['data'] = flow.request.content
        task_new(flow.request.url, options)
        r = redis.Redis('127.0.0.1')
        r.set(key, '1')
    return True

#check post
def check_post(flow):
    headers = flow.request.headers
    print headers['Content-Length']
    # < 10k
    if not (headers['Content-Length'] and headers['Content-Length'][0]) or int(headers['Content-Length'][0]) > 10240:
        return False
    return True

def response(context, flow):
    query_dict = flow.request.get_query()
    method = flow.request.method.lower()
    code = flow.response.code
    url = flow.request.url
    content_type = ''
    if flow.response.headers['Content-Type'] and flow.response.headers['Content-Type'][0]:
        content_type = flow.response.headers['Content-Type'][0]

    if code == 200 and allow_type(content_type):
        if method == 'get':
            if query_dict:
                send_sqlmap(flow)
        elif method == 'post':
            if check_post(flow):
                send_sqlmap(flow, 'post')
            #print 'post'
        #print get_url_path(url)


#    flow.response.headers["newheader"] = ["foo"]

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
    options['url'] = target_url
    options['stopFail'] = True
    options['randomAgent'] = True
    url = prefix + '/scan/' + task_id + '/start'
    response = curl_sqlmap('post', url, json.dumps(options))

    print target_url
    print prefix + '/scan/' + task_id + '/log'

def url_exist(key):
    r = redis.Redis('127.0.0.1')
    if r.get(key):
        return True
    return False

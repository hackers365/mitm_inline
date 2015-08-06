import re
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

def allow_type(content_type):
    allow_list = ['text/html', 'text/plain']
    for i in allow_list:
        if re.search(i, content_type):
            return True
    return False

def response(context, flow):
    query_dict = flow.request.get_query()
    method = flow.request.method.lower()
    code = flow.response.code
    content_type = flow.response.headers['Content-Type'][0]

    if code == 200 and :
        if method == 'get' and query_dict:
            print flow.request.url


#    flow.response.headers["newheader"] = ["foo"]

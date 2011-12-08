=================
API Documentation
=================

Bancha ships with a complete API system that permits your apps to completely integrates with the Bancha framework.
By default, **GET** and **POST** requests are accepted.
All responses have the same **JSON** prototype that consists in three keys:

1. **status** (the HTTP status code)
2. **data** which contains the requested data
3. **message**, a short string indicating the status of the request.

Before all, the client must **login** with his username/password. Bancha will returns a **unique token** that needs to be sent on each requests that requires authentication.

Note: the **URL** of any API method needs to be called by prefixing the administration path, such as **admin/api/login**.

---------
api/login
---------

Returns you the **token** on success.
The token needs to be sent on each requests that requires authentication.

 * Method: **GET/POST**
 * Response: **Json**
 * Params: **username** (string), **password** (string)::

    //Example of call:
    www.example.org/admin/api/login

    username = demo
    password = demo

    //Outputs on success:
    {"status":200,"data":{"token":"abcdefg123456789"},"message":"OK"}

    //Outputs on failure:
    {"status":403,"data":[ ],"message":"USER_PWD_WRONG"}

-----------
api/records
-----------

**Needs authentication.**
This API gives you the ability to query the Bancha ORM system such as the Records class.
We called this feature **ActiveQuery**, and all the methods remains the same of the model methods.
It always requires two parameters: **token** and **query**.

 * Method: **GET/POST**
 * Response: **Json**
 * Params: **token** (string), **query** (string)::

    //Example of call:
    www.example.org/admin/api/records

    token = abcdefg123456789
    query = type:Blog|limit:1|order_by:id_record,DESC|get

    //Outputs:
    {
        "status":200,
        "data": {
            "records": [
                {"id_record":"1","id_type":"2","date_insert":"1319743682","title":"..."}
            ],
            "count":1
        },
        "message":"OK"
    }


As you can see, the syntax is very similar to the PHP one. Check this other example::

    //PHP Syntax:
    $posts = $this->records->type('Products')->like('title', 'Hello')->limit(5)->get();

    //Same result with ActiveQuery syntax:
    type:Products|like:title,Hello|limit:5|get

    //Pretty simple, uh?

    //Another PHP example:
    $last_post = $this->records->type('Blog')->order_by('date_publish', 'DESC')->get_first();

    //And the same record using the ActiveQuery syntax:
    type:Blog|order_by:date_publish,DESC|get_first


When no records are found, you will receive an output with this prototype::

    {"status":200,"data":[],"message":"NO_RECORDS"}


**Note: Functions that accepts arrays are not supported by the ActiveQuery system yet.**


----------
api/logout
----------

**Needs authentication.** Destroys the current token.

 * Method: **GET/POST**
 * Response: **Json**
 * Param: **token**::

    token = abcdefg123456789

    //Example of call:
    www.example.org/admin/api/logout

    //Outputs:
    {"status":200,"data":[],"message":"OK"}


Back to :doc:`../index`
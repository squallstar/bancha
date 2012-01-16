==================
Records (Contents)
==================

The **Records model** is the heart of Bancha! Is used to extract the records of Bancha. Is automatically loaded by the framework, and you can access it on the MVC in these two simple ways::

    // 1. Around the MVC
    $this->records

    // 2. Inside the views of a theme using the find() function
    //    of the frontend helper that is automatically loaded by the themes.
    find()


The model has a big number of functions that you can use to refine your extractions. First of all, you need to filter by one or more **Content types** as follows::

    $products = $this->records->type('Products')->get();

    //or passing an array of content types
    $both = $this->records->type(array('Products', 'Photos'))->get();

    //Inside the themes, you will likely use the find function passing the type/s
    //as first parameter:
    $products = find('Products')->get();

**Note: the type/find function must be used before calling other functions (such as where, order_by, etc..).
The **get()** function that you just saw is used to extract the :doc:`../core/record` after placing the conditions. Remember to use it as last method to start the extraction!

---------------
Where condition
---------------

**where($column, $val)** and **or_where($column, $val)**

You can add where conditions on physical columns as well as **xml columns**::

    $posts = find('Blog')->where('title', 'Foo')->get();

    $products = find('Products')->where('id_record !=', 5)->get();

    //You can add more than one where conditions:
    $prods = find('Products')->where('date_publish > ' . time())
                             ->where('id_record', 4)
                             ->or_where('title !=', 'Foo')
                             ->get();

--------------
Like condition
--------------

**like($column, $val)** and **or_like($column, $val)**

The like condition works identical to the above **where condition**::

    $pages = find('Menu')->like('title', 'Home')->get();

    $posts = find('Products')->like('title', 'Helmet')->get();

    //You can combine the like condition with other conditions:
    $prods = find('Products')->where('date_publish > ' . time())
                             ->like('title', 'Helmet')
                             ->or_like('title', 'Hats')
                             ->get();


------------------
Where_in condition
------------------

**where_in($column, $val [, $escape])**

Similar to the where condition, but accepts an array as second parameter::

    $posts = find('Blog')->where_in('id_record', array(1, 2, 3))->get();


--------
Order by
--------

**order_by($column, $direction)**

Sets a standard **SQL order by**:: 

    $posts = find('Blog')->like('title', 'News')->order_by('date_publish', 'DESC')->get();


-----------------
Limit the results
-----------------

**limit($num, $offset)**

Sets a standard **SQL limit**:: 

    $posts = find('Blog')->order_by('date_publish', 'DESC')->limit(10)->get();


-------------
Count records
-------------

**count()**

You can use the count function instead of the **get()** one, to count records::

    $num = find('Blog')->like('title', 'News')->count();


------------------------
Extract linked documents
------------------------

**documents($bool)**

Documents of each record are not automatically extracted. You can extract the documents calling the **documents** function before the get() method::

    $posts = find('Blog')->documents(TRUE)->limit(10)->get();

If you wish, you can extract the documents later, using the **set_document** function of the :doc:`../core/record`.


Back to :doc:`../index`
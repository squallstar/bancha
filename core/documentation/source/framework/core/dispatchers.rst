===========
Dispatchers
===========

Dispatchers are classes that performs many operation before the rendering process. They are tipically fired by the controllers.
As example, the **Website** controller fires many of the dispatchers described below.

The Bancha core dispatcher are located inside the **core/dispatchers** directory.

Instead, the folder for the dispatchers of your application (if you need to create a new one), is **application/dispatchers**.
To add a new dispatcher, create a new file in that directory using **dispatcher_name.php** as filename and **Dispatcher_name** as **class name**.

You can find a sample dispatcher here: **application/dispathcers/dispatcher_example.php**

You can load a dispatcher using the following function::

    $this->load->dispatcher('foo');

    //Then, use it as an object of the super-object
    $this->dispatcher->method_name();

    //You can specify the name as second optional parameter
    $this->load->dispatcher('name', 'foo');
    $this->foo->method_name();


------------------
Default dispatcher
------------------

This is the default dispatcher of the website: is responsable of allocating the current **page**, the optional **record/records** and loading the rendering process.
Called from the **router** action of the **Website** controller::

    $this->load->dispatcher('default');
    $this->dispatcher->start();


----------------
Image dispatcher
----------------

This dispatcher handles the :doc:`/framework/core/imagepresets`. When a preset cached file does not exists, an action on the **website controller** will be called and that action then fires the image dispatcher. This, will performs all the graphical preset operations and after saving the file on the disk (to cache the next request) will output the content to the client.
Called from the **image_router** action of the **Website** controller::

    $this->load->dispatcher('images');

    $data = array(
        'type'      => 'Products',
        'field'     => 'first_image',
        'id'        => 123,
        'preset'    => 'little-square',
        'filename'  => 'path/to/original.jpg',
        'ext'       => 'jpg'
    );

    $this->dispatcher->retrieve($data);

Read more about the :doc:`/framework/core/imagepresets`.


--------------------
Resources dispatcher
--------------------

This dispatcher can minify **css** and **javascript** resources. It will be automatically used when using the **minify** function of the :doc:`../helpers/frontend`. That function generates a URL that will be routed to the resources dispatcher and the requested resources will be compressed, merged, cached and then sent to the client.
Called from the **minify** action of the **Website** controller::

    $resources = array('path/js/jquery.js', 'path/js/application.js');

    $this->load->dispatcher('resources');
    $this->dispatcher->minify('themename', $resources, 1);


----------------
Print dispatcher
----------------

This dispatcher can convert any of the website pages to PDF file. Is automatically used by the **default dispatcher** when an URL is appended with **/print.pdf** after the page URL.

To works, needs the **DOMPDF library** that is not included by default (is large and un-necessary for the most of the websites).
In order to use the dispatcher, you need to download the library `here <http://code.google.com/p/dompdf/>`_.
Then, place the library under **core/libraries/externals/dompdf** and you're done::

    //Standard page:
    http://example.org/my-first-page

    //PDF page:
    http://example.org/my-first-page/print.pdf

You can manually load the dispatcher to use it as you want in this way::

    $this->load->dispatcher('print');
    $this->dispatcher->render($page);


Back to :doc:`../index`.
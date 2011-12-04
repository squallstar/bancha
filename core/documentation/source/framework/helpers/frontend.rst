===============
Frontend helper
===============

This helper is automatically loaded by the framework (Default Dispatcher) and contains many useful functions for the front-end of the website.
The helper is located here: **core/helpers/frontend_helper.php**


----------------
Rendering a view
----------------

**render( $viewname )**

This function simply renders a view inside the current theme.
Usage::

    //Renders the file views/header.php inside the theme folder
    render('header');

    //Renders the file /views/extra/sidebar.php inside theme theme folder
    render('extra/sidebar');


---------------------------
Rendering the page template
---------------------------

**template( )**

Renders the template of the current page. Tipically is used once on each theme, between the <body> tag.
Usage::

    <body>
        <?php template();
    </body>


--------------
Load a setting
--------------

**settings( $key [, $area] )**

A wrapper of the Bancha **$this->settings** model: searchs for a key inside the default settings, or the specifig settings of an area when is given as second parameter. Usage::

    <title> <?php echo settings('website_name'); ?> </title>


--------------------------
Display the content render
--------------------------

**content_render( )**

Renders the **content_render** contained under the **views** folder of a theme. Tipically this function is called inside the **views/templates**.
Usage::

    render('header');

    //Loads the views/content_render.php file
    content_render();

    render('footer');


---------------------------
Get a specific Content type
---------------------------

**type( $name )**

Returns the Content type given its name or ID. Usage::

    $blog_type = type('Blog');


--------------
Load an helper
--------------

**load_helper( $name )**

Loads an helper inside the **application/helpers** or **core/helpers** folders. Usage::

    load_helper('breadcrumbs');


---------------------
Load and get a module
---------------------

**module( $name )**

Loads and returns a module. To learn how to add custom modules inside your application, visit the **Module** documentation section. Usage::

    //Loads the sharer module
    $sharer = module('sharer');

    //And renders the module
    $sharer->render();


----------------------------------------
Find records of a specified content type
----------------------------------------

**find( $type_name)**

You can easily search throught your records using the records model inside a theme. To access that model, use the **find** functions as follows in the examples below::

    //Gets the last 10 posts
    $posts = find('Blog')->limit(10)->order_by('date_publish', 'DESC')->get();

    //Searchs for a specific record
    $product = find('Products')->where('title', 'Teabag')->get_first();

 
-------------------------------------
List the categories of a content type
-------------------------------------

**categories( $type_name)**

You can easily get the categories of a content type using the categories model inside a theme. To access that model, use the **categories** functions as follows in the examples below::

    //Gets the categories of the content type "Blog"
    $categories = categories('Blog')->get();

    //Get the categories of the content type that a page is listing
    $categories = categories( page('action_list_type') )->get();


---------------------------------
Get a field from the current page
---------------------------------

**page( $field )**

Returns the value of a single field of the current page. When the field key is not passed, the entire **Page Object** will be returned back. Usage::

    <h1> <?php echo page('title'); ?> </h1>


-----------------------------------
Displays the page custom CSS and JS
-----------------------------------

**page_js( )** and **page_css( )**

Displays the custom css and javascript code of the current page. Put this in the head section of your layout. Usage::

    //Outputs the JS <script> tag
    page_js();

    //Outputs the CSS <style> tag
    page_css();


-------------------------------------
Displays the page Feed (RSS-XML) link
-------------------------------------

**page_feed( )**

Displays the custom css and javascript code of the current page. Put this in the head section of your layout. Usage::

    //Outputs the Feed <link> tag
    page_feed();


------------------------------
Get a tree of the website menu
------------------------------

**tree( $which_one )**

Returns one of the available trees in the environment. Can be used without parameters to get the website default menu, or passing **current** or **breadcrumbs**. Usage::

    //Returns the website menu
    $menu = tree();

    //Returns the menu, using the current page as starting point
    $menu = tree('current');

    //Returns the breadbrumbs tree
    $bredcrumbs = tree('breadcrumbs');

    //Prints the html using the "menu" helper
    echo menu($tree);


-----------------------------------
Get a field from the current record
-----------------------------------

**record( $field )**

Returns the value of a single field of the current record. When the field key is not passed, the entire **Record Object** will be returned back. The record object is only available when visiting the child of a page. Usage::

    <h2> <?php echo record('title'); ?> </h2>


-----------------------------------
Checks whether a page has childs
-----------------------------------

**have_records( )**

Returns a boolean indicating if the page has some records linked. The records are available only while you're in a page with the action "List". Usage::

    if (have_records())
    {
        //This page has some records
    }


-----------------------------------
Get the child records of a page
-----------------------------------

**records( )**

Returns an array of the Record childs of a page. The records are available only while you're in a page with the action "List". Usage::

    if (have_records())
    {
        $page_records = records();
    }


----------------------------------------
Get the title of the current page/record
----------------------------------------

**title( )**

Returns the title string of the current page/record. Usage::

    <title> <?php echo title(); ?> </title>


----------------------------------------------------
Get the page/record author, keywords and description
----------------------------------------------------

**page_author( )** , **page_keywords( )** , **page_description( )**

Returns author, keywords and description of the current page/record to be used in their own meta tags. Usage::

    <meta name="description" content="<?php echo page_description(); ?>">
    <meta name="keywords" content="<?php echo page_keywords(); ?>">
    <meta name="author" content="<?php echo page_author(); ?>">


----------------------
Display the pagination
----------------------

**pagination( )**

Renders the pagination of a record list when available. Usage::

    <?php echo pagination(); ?>


------------------------
Get the current language
------------------------

**language( )**

Simply returns the current language. Usage::

    <?php echo language(); ?>


-------------------------------
Renders the available languages
-------------------------------

**languages( [$separator] )**

Renders the languages using **anchor tags** and separating them by using the provided separator. Usage::

    <?php echo languages(' - '); ?>
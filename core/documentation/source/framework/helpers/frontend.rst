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

---------------------------------
Get a field from the current page
---------------------------------

**page( $field )**

Returns the value of a single field of the current page. When the field key is not passed, the entire **Page Object** will be returned back. Usage::

    <title> <?php echo page('title'); ?> </title>


-----------------------------------
Displays the page custom CSS and JS
-----------------------------------

**page_js( )** and **page_css( )**

Displays the custom css and javascript code of the current page. Put this in the head section of your layout. Usage::

    page_js();
    page_css();
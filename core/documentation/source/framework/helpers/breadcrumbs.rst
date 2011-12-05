==================
Breadcrumbs helper
==================

The breadcrumbs helper is located inside **core/helpers/breadcrumbs_helper.php** and needs to be **manually loaded** using the default loader as follows::

    //Inside controllers and models:
    $this->load->helper('breadcrumbs');

    //Inside the views (themes):
    load_helper('breadcrumbs');

    //Outside of the MVC
    $B = & bancha();
    $B->load->helper('breadcrumbs');


-------------------------
Generate HTML breadcrumbs
-------------------------

**breadcrumbs( $breadcrumbs_array [, $separator] )**

Prints the breadcrumbs using "span" and "a" tags.
If the link of a breadcrumbs is different from the current url, an anchor will be printed inside each span.
Usage::

    //Example using the website breadcrumbs contained in the model tree
    echo breadcrumbs( tree('breadcrumbs') );

    //Or passing an array
    $tree = array(
    	  array('link' => '/', 'title' => 'Home')
    	, array('link' => '/blog', 'title' => 'Blog')
    );
    echo breadcrumbs($tree);
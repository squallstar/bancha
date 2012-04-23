====
Tree
====

The tree model is used to **extract the pages**. Is automatically loaded by the framework, and contains many useful function.
Tree are cached on the filesystem inside the **application/cache/_bancha** using the content type plus the languages as name. You can clear the cache of the trees using the function below::

    $this->tree->clear_cache();

    //Or just on a single content type:
    $this->tree->clear_cache('Menu');

**Note: the trees cache is automatically cleared when you manage the pages through the Bancha administration.**


------------------------
Extract the website tree
------------------------

To extract the pages of a website (using the content types defined in the config file) you can use the **get_default** function::

    $website_menu = $this->tree->get_default();

    //If you are inside a theme, you could also use the tree() function to get the same result:
    $website_menu = tree('default');


----------------------------
Extract a branch of the tree
----------------------------

To extract a branch of the tree (**its child pages**), you can use the **get_default_branch()** function passing the **id** of the page/record as first parameter::

    //Our current page
    $page

    //The branch of the current page
    $childs = $this->tree->get_default_branch($page->id);


--------------------------------------
Extract the branch of the current page
--------------------------------------

To extract the branch of the current page, you can use the **get_current_branch()** function::

    $childs = $this->tree->get_current_branch();

    //Inside a theme, you can also reach the same result using the tree() function:
    $childs = tree('current');


---------------------
Extract a custom tree
---------------------

If you need to extract a custom tree, you can use the many functions of the tree model such as the following example::

    $tree = $this->tree->type('Menu')->where('id_parent', 2)->get();
    

Back to :doc:`../index`
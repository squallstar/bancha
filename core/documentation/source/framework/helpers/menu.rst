===========
Menu helper
===========

This helper is automatically loaded by the **framework** and is located inside **core/helpers/menu_helper.php**.

---------------------
Generate an HTML menu
---------------------

**menu( $tree [, $max_depth=99, $starting_level=1, $show_in_menu='T' ] )**

Prints a recursive html tree using "ul", "li" and "a" tags.
The tree must be extracted using the **/framework/models/tree**.
Inside a theme, the view already contains a variable named **$tree** with the website default menu.
The **ul** tag is will have a "menu" class, and the **li** tags will be automatically marked with the "open" and "selected" classes, based on the current user position inside the tree.

Basic usage::

    //Prints the default tree of the website
    echo menu($tree, 2);

    //Prints the tree below the current level
    echo menu( $this->tree->get_current_branch() );
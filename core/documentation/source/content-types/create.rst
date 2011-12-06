######################
Add a new Content type
######################

To create a new content type, click on the "Content types" section, and the content types list will appear. On the top right side of the page, you will find the **Add new content type** button, click it!

The form asks you to fill some fields in order to create a new content type, as described here below.

===========
Form fields
===========

----
Name
----

The **unique key** of a content type: must be an alphanumerical single word, no whitespaces or dashes! Values similar to **News**, **Products**, **Posts**, **Comments** are ok::

    // News		-> Ok
    // Products		-> Ok
    // Nèws!		-> BAD!
    // My Products	-> BAD!
   
This name will be used everywhere in the application: from the database queries to the name of the XML scheme.


-----------
Description
-----------

Here you can write one or more words that will be used as **label** of the content type in the administration (left menu, selects, etc..). Tipically for a content type named "Posts" you will write "Blog posts". This field **allows whitespaces** and other common characters.


--------------
Type structure
--------------

The structure of a content type completely decides the behaviour that the type will have. The generated XML scheme will also be different from a **simple** to a **tree** structured type.

^^^^^^^^^^^^^^^^^
Simple (Contents)
^^^^^^^^^^^^^^^^^

Contents of this type cannot be organized hierarchically. It's perfect when you have to define linear contents such as **news**, **products** or the **posts** of a blog.


^^^^^^^^^^^^
Tree (Pages)
^^^^^^^^^^^^

When you have to organize hierarchically the contents (chid-parent relation) this kind of structure would be the best.
By default, the pages of the website are linked to the **Menu** content type, which is of tree structure.

Each record of the tree structured content types can have a unique parent and infinite childs, so it's perfect for "tree shaped" contents like the pages of a website.

==========
The scheme
==========

After click the **Add** button, Bancha will generate a new XML scheme based on the options you selected above. The generated scheme will have the **Name** of the content type plus the **.xml** extension, and will be stored here::

    application/xml/Name.xml

On the next chapter you will understand how to **edit and extend** this base scheme to virtually create whatever you want.

Next: :doc:`scheme`
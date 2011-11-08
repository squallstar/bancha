#################################
Understanding Bancha
#################################

It's very important to understand how Bancha works, before using it.

Bancha has been built to manage any type of content. This means that teoretically you can manage an infinite number of **things**.

Any different kind on thing, in Bancha becomes a **Content type**.

* The news list of a website, will be a content type named **News**.
* The posts of a blog, will be a content type named **Posts** and the linked comments will be another content type named **Comments**.
* If you want to build an e-commerce, you may need to create also a **Products** content type.

Feel free to create tens of different content types.

================
Bancha's pillars
================

-------------
Content types
-------------

How can a content types be differents between each others?
Every content types have a unique **scheme** that describes it.

The **News** content type will have a scheme, the **Comments** content type will have a different scheme and so on.

Basically, a **Scheme** consists in a single **XML** file that describes all the fields that the **Records** of that content type needs to implement.
You will learn how to create and manage the content types and their schemes in the next chapters of the documentation.


^^^^^^^^^^^^^^^^^^^^^^^^
Hierarchically structure
^^^^^^^^^^^^^^^^^^^^^^^^

A content type could be of type **Pages**: it means that its records can be organized hierarchically. Each record can have a unique parent and infinite childs, so it's perfect for "tree shaped" contents.


^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Non-hierarchically structure
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

These content types cannot be organized hierarchically. It's perfect when you have to define linear contents such as **news**, **products** or the **posts** of a blog.


----------------
MVC Architecture
----------------

Bancha it's all written using the MVC paradigm.
Before starting with the tutorial, take a look at the :doc:`architecture`.


---------------------
Introduction tutorial
---------------------

To make your first steps with Bancha, follow the introduction tutorial: :doc:`/firststeps/getting-started`
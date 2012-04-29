###################
Defining a relation
###################

Sometimes you will need to set-up a relation between two different content types. Bancha XML schemes permits you to define 1-0, 1-1 and 1-n relations.
Relations are defined inside the **content** node of the content type as follows::

    relations :
        relation_name :
            type : 1-n|1-0|1-1
            with : Destination content type name
            from : Fieldname of source content type
            to   : Fieldname of destination content type

or using XML::

    <?xml version="1.0" encoding="utf-8"?>
    <content id="1">
        <name>Pages</name>
        ...
    	<relation name="" type="" with="" from="" to="" />
    	....
    </content>

---------------------
1-0 and 1-1 relations
---------------------

Used when each record of the content type can be linked to none or just one record.

Definition example::

    relations:
        color:
            type : 1-1
            with : Colors
            from : id_record
            to   : id_color

or using XML::

    <relation name="color" type="1-1" with="Colors" from="id_record" to="id_color" />

------------
1-n relation
------------

Used when the record can have more than one childs, such as the **comments of blog posts**.
Example: we will define this relation on the content type "Blog"::

    relations :
        comments :
            type : 1-n
            with : Comments
            from : id_record
            to   : post_id

or using XML::

    <relation name="comments" type="1-n" with="Comments" from="id_record" to="post_id" />

In the above example, we are setting a relation named **comments** of type **1-n**, between the field **id_record** of the content type "Blog" to the field **post_id** of the content type named **Comments**.

Likewise, on the **Comments** scheme you will define the opposite relation as follows::

    relations :
        post :
            type : 1-1
            with : Blog
            from : post_id
            to   : id_record

or using XML::

    <relation name="post" type="1-1" with="Blog" from="post_id" to="id_record" />

This system permits you to **set up a relation** between a Record and its childs. On the next sections of the documentation you will discover how to work with records. Setting up a relation between a content type "Blog" and its comments gives you the ability to gets all the comments of a post at a glance.

For more informations, read the next section: :doc:`use`
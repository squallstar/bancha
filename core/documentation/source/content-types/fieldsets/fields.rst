######
Fields
######

In a MVC architecture, a field is part of a single content type and is used to store an attribute on the Record class.
For humans, **field** nodes are located inside :doc:`index` and are used to describe a single field of a content type. 

Eg: if your content type is named **Products**, you will likely have fields for its **SKU**, **Name**, **Color**, etc...

Below you can see the definition of a simple **input field**::

    title :
        column      : true
        description : Title
        type        : text
        rules       : required

or, if you're using XML schemes::

    <field id="title" column="false">
        <type>text</type>
        <description>The title</description>
        <rules>required</rules>
    </field>

Let's talk about it: first of all, a field must have a **unique id** that will be used as **key** to store the value.

Second, you will see the **column** attribute: this boolean describes if this attribute needs to be saved into a physical or logical column. Choose **true** if the table of your content type (the default is **records**) contains a column named as the **id** attribute. Otherwise, the value will be stored in the **xml** column, serialized with some other fields into an XML.
If you are planning to do many intense query operations on this field, using a physical column will greatly improves your performances.

**Note:** you don't have to add/remove the physical columns by hand: after changing a scheme, go on the **Content types** page of the administration and click **Rebuild tables** on the content type. Bancha will automatically alter the tables for you.

The **<type>** node describes what kind of field, this field will be. The other fields are optional, and you can easily see the meanings reading them.


-----------
Field types
-----------

.. toctree::
   :maxdepth: 1

   fields-text
   fields-selection
   fields-hierarchy
   fields-images
   fields-files
   fields-hidden
   fields-date
   fields-number


---------------
Field shared features
---------------

Each kind of field can implement these features:

.. toctree::
   :maxdepth: 1

   shared-default
   shared-rules
   shared-list
   shared-admin
   shared-visible
   shared-onkeyup
   shared-onchange


Go back to :doc:`/content-types/scheme`
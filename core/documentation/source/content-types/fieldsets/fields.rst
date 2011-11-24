######
Fields
######

In a MVC architecture, a field is part of a single content type and is used to store an attribute on the Record class.
For humans, **field** nodes are located inside :doc:`index` and are used to describe a single field of a content type. 

Eg: if your content type is named **Products**, you will likely have fields for its **SKU**, **Name**, **Color**, etc...

Below you can see the definition of a simple **input field**::

    <field id="title" column="false">
        <type>text</type>
        <description>The title</description>
        <rules>required</rules>
    </field>

Let's talk about it: first of all, a field must have a **unique id** that will be used as **key** to store the value.
Second, you will see the **column** attribute: this boolean describes if this attribute needs to be saved into a physical or logical column. Choose **true** if the table of your content type (the default is **records**) contains a column named as the **id** attribute. Otherwise, the value will be stored in the **xml** column, serialized with some other fields into a XML.
If you are planning to do many intense query operations on this field, using a physical column will greatly improves your performances.

The **<type>** node describes what kind of field, this field will be. The other fields are optional, and you can easily see the meanings reading them.


-----------
Field types
-----------

.. toctree::
   :maxdepth: 1

   fields-text
   fields-selection
   fields-hierarchy


---------------
Shared features
---------------

Each kind of field can implement these features:

* :doc:`shared-default`
* :doc:`shared-rules`
* :doc:`shared-list`
* :doc:`shared-admin`
* :doc:`shared-visible`
* :doc:`shared-onkeyup`
* :doc:`shared-onchange`


Go back to :doc:`/content-types/scheme`
================
Hidden fields
================

Using the hidden type, you can specify an hidden field. Bancha itself uses them for the the **record_id** and **type_id** fields inside each Record, since these fields exist but should not be displayed on the form.

In the HTML document, these fields will be declared using an **input** of type **hidden**.

Below you can find a sample implementation using YAML::

	id_type :
  		column  : true
  		kind    : numeric
  		type    : hidden
  		list    : true
  		default : 1

or using XML::

    <field id="id_type" column="true">
        <type>hidden</type>
        <kind>numeric</kind>
        <list>true</list>
        <default>1</default>
    </field>

Back to :doc:`fields`.

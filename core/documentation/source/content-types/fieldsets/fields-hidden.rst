================
Hidden fields
================

With the hidden type, you can specify an hidden field. Bancha, uses them for the the **record id** and **type id** of each Record, because these fields exists but they don't have to be displayed on the form.

These fields are declared as **input** of type **hidden**.

Below you can find a sample implementation using YAML::

	id_type :
  		column  : true
  		kind    : numeric
  		type    : hidden
  		list    : true

or using XML::

    <field id="id_type" column="true">
        <type>hidden</type>
        <default>1</default>
    </field>

Back to :doc:`fields`.
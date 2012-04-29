=====================
Admin list visibility
=====================

Throught the **admin** boolean node, you can define whether the field needs to be displayed on the administration "record list" page. When is set to false, the field will be displayed only on the "record edit" admin form.

A filter will be also displayed on the administration lists to refine your searchs.

Basic usage using YAML::

	title :
		...
		admin : true
		...

or using XML::

    <field id="title">
    	....
        <admin>true</admin>
        ....
    </field>


Back to :doc:`fields`.
===============
List extraction
===============

The **<list>** boolean node, defines whether a field needs to be extracted on the content list pages.
When is omitted or setted to "false", the field will be extracted only on the record detail page.

Basic usage using YAML::

	title : 
		...
		list : true
		...

or using XML::

    <field id="title">
    	....
        <list>true</list>
        ....
    </field>


Back to :doc:`fields`.
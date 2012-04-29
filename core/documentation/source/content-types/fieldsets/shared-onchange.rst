===========================
Javascript "onchange" event
===========================

With the **onchange** node, you can define some javascript code that will be executed on the field **onchange** event.

Basic usage using YAML::

	title :
		...
		onchange : myJsFunction();
		...

or using XML::

    <field id="title">
    	....
        <onchange>myJsFunction();</onchange>
        ....
    </field>

This event will be triggered using the **jQuery .change() method** on **select**, **radio**, **checkboxes** and other similar fields.


Back to :doc:`fields`.
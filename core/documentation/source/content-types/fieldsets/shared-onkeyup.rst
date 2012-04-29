==========================
Javascript "onkeyup" event
==========================

With the **onkey** node, you can define some javascript code that will be executed on the field **onkeyup** event.

Basic usage using YAML::

	title :
		...
		onkeyup : myJsFunction();
		...

or using XML::

    <field id="title">
    	....
        <onkeyup>myJsFunction();</onkeyup>
        ....
    </field>

This event will be triggered on **input** and **textarea** fields.


Back to :doc:`fields`.
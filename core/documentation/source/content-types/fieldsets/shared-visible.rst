======================
Field visibility (CSS)
======================

Throught the **visible** boolean node, you can define whether the field needs a css hidden class to be applied on it (only on administration "record edit" form).
Use this feature when you need to display the field on the edit form only on certain events, or programmatically by javascript.

Basic usage::

	title :
		...
		visible : false
		...

or using XML::

    <field id="title">
    	....
        <visible>false</visible>
        ....
    </field>

By default, fields are visible.


Back to :doc:`fields`.
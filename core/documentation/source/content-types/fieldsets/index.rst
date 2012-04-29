#########
Fieldsets
#########

A fieldset, as in HTML is just a set of :doc:`fields`. A fieldset must have a unique name, and every content type can have infinite fieldsets.
Each fieldset will be a section made by one or more fields (you will likely see many **<field>** nodes and fews fieldsets).

**Use fieldsets to organize semantically the fields on the administration.**

A fieldset can also include a **16x16 px** icon, using the **"icon"** attribute.
The available icons are included under **themes/admin/widgets/schemes_icons**. Feel free to add many other icons as you need.

This will be a tipical structure of a **fieldset** node::

	fieldsets :
		- name   : General informations
		  icon   : page
		  fields :
		  	my_field : ...
		  	other_field : ...
		  	third_field : ...


or, if you're using XML schemes::

    <fieldset name="General informations" icon="page">
        <field></field>
        <field></field>
        <field></field>
        ...
    </fieldset>


Next: :doc:`fields`

Back to :doc:`/content-types/scheme`
================
Hierarchy fields
================

Using the hierarchy field will display **multiple choices** tree map, using the main Bancha hierarchies as default **source**.

You can also choose to implement different hierarchies using the **options** node. Read the **Selection fields** for more informations about the options node: :doc:`fields-selection`.

Below you can find a sample implementation using YAML::

	action_list_hierarchies
		description : Select one or more hierarchies
		type : hierarchy

or using XML::

    <field id="action_list_hierarchies">
        <description>Select one or more hierarchies</description>
        <type>hierarchy</type>
    </field>


Back to :doc:`fields`.
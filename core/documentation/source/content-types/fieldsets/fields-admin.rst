=====================
Admin list visibility
=====================

Throught the **<admin>** boolean node, you can define whether the field needs to be displayed on the administration "record list" page. When is set to false, the field will be displayed only on the "record edit" admin form.

If you need to make searchs filtering by a specific field, tipically you will set the admin node to **true**.

Basic usage::

    <field id="title">
    	....
        <admin>true</admin>
        ....
    </field>


Back to :doc:`fields`.
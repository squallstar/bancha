=============
Default value
=============

Every kind of field can implement the **default** node. It indicated the default value of the field and is useful to setup an initial input to a field (mainly used on select/radio fields).

Basic usage using YAML::

	title :
		column  : true
		default : Hello
		type    : text

or using XML::

    <field id="title" column="true">
        <default>Hello</default>
        <type>text</type>
    </field>


Note: when used on **select**, **radio** or **checkbox** fields you must indicate the **value** of the option, not the associated **label**.

You can also evaluate **php functions/variables** adding the **eval:** prefix to the text as follows in the examples below.

YAML::

	default : eval:time()

	default : eval:$this->lang->default_language

XML::

    <default>eval:time()</default>

    <default>eval:$this->lang->default_language</default>


Back to :doc:`fields`.
================
Selection fields
================

These fields are used to store one or more values and they differs from other field types because they have **options**.
Visually, these fields are **select**, **radio**, **checkbox** and **multiselect**.

----------
Definition
----------

The type node is required, and can be one of these four options:

* select
* radio
* checkbox
* multiselect


-------
Options
-------

The **options** node is required if you don't have a **sql** field (see some paragraphs below).
This node can describe the options using one of the following three different formats depending on your needs:

1. Hard-coded values
2. Eval values
3. SQL-Query extraction

Using the first one, you just need to write down the options using a key/val syntax if you are using YAML schemes, or the standard HTML syntax if you're using XML schemes.

YAML::

    options :
        T : Yes
        F : No

XML::

    <options>
        <option value="T">Yes</option>
        <option value="F">No</option>
    </options>

Otherwise, the "Eval values" format gives you the possibility to extract the options as a result of an **eval**. You can extract a variable allocated by the framework or just evaluate some PHP code.
Check out this example::

    options :
        custom : config_item('website_languages_select')

or using XML::

    <options>
        <custom>config_item('website_languages_select')</custom>
    </options>

The last possible format is the **SQL-Query** extraction: using an xml-like syntax, you can write simple queries to populate the fields. You can also decide whether the query needs to be cached by the system.
You query must **select** two values: the **name** and the **value**, respectively the label used in the field and the option value.
Please note that in this format the **options** node is **NOT** used: you have to use the **sql** node as the example below::

    sql :
        cache    : false
        select   : "id_record AS value, title AS name"
        from     : records
        type     : Products
        where    : published = 1
        order_by : name ASC

or using XML::

    <sql cache="false">
        <select>id_record AS value, title AS name</select>
        <from>records</from>
        <type>Products</type>
        <where>published = 1</where>
        <order_by>name ASC</order_by>
    </sql>

Settings the cache to **true**, let your query to be cached until the content-types cache will be cleared.

Below you can find an example of a select field::

    lang :
        column      : true
        description : Language
        type        : select
        admin       : true
        list        : true
        default    : eval:$this->lang->default_language
        length      : 2
        options     :
            custom : config_item('website_languages_select')

and here is the same field using xml syntax::

    <field id="lang" column="true">
        <description>Language</description>
        <type>select</type>
        <admin>true</admin>
        <list>true</list>
        <options>
            <custom>config_item('website_languages_select')</custom>
        </options>
        <default>eval:$this->lang->default_language</default>
    </field>


Back to :doc:`fields`.
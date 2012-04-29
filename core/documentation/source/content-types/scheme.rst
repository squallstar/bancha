################
YAML/XML Schemes
################

Each content type is configured by a single YAML/XML file describing all its fields and is placed into **application/xml/types/** directory.
When you create a new content type, it's scheme file contains a simple structure with some "starters" fields.

The schema used for **tree-structured** content type definition is a bit different from the **simple-structured** one, since it includes more option for displaying custom content and some other things.
You can edit this file from the administration section (click on the type name on the content types list), or by hand (the best choice) with your favorite editor. The basic structure of a YAML content type is defined as follows::

    id  : 1
    name: "Pages"
    descriptions:
      full_name  : "Website pages"
      new_record : "New page"
    tree: false
    parents :
      - Pages
    fieldsets :
      - name   : General informations
        icon   : page
        fields :
          id_record :
            column : true


or, if you have chosen to use the XML syntax::

    <?xml version="1.0" encoding="utf-8"?>
    <content id="1">
        <name>Pages</name>
        <descriptions label="Website pages" new="New page" />
        <tree>true</tree>
        <table key="id_record" production="records" stage="records_stage" />
        <order_by field="date_update" sort="DESC" />
        <fieldset name="Sample fields">
            <field id="first"></field>
            <field id="second"></field>
            <field id="third"></field>
        </fieldset>
        <parents>
        	<type>Pages</type>
        </parents>
    </content>

**NOTE: Content types are cached on the file "application/cache/_bancha/content.tmp" for faster access. After editing a scheme, you need to delete that file or you can just click on the "Clear cache" link on the left menu of the administration**. If you edit the scheme by the "edit scheme" section, the cache will be automatically cleared after saving the scheme.

When you create a new content type, Bancha will automatically sets a value to the id attribute of the **content** node; Bancha will similarly set the values to the **name** and **description** nodes which defines the value used as key and the value read by the user into the panel (the label attribute set the value which will be displayed, whereas the new attribute set the label/link used to insert a new content of the same type). 

The **<tree>** node contains a boolean value defining if we are declaring a flat or hierarchical content (do you remember the difference between pages and contents? See :doc:`index`). 
The **<parents>** node is mandatory for hierarchical content type and contains a list of the content type names which refer to the parent pages. Initially we have a single node which refers to the content type being defined. 

The **<table>** node defines the production table, the staging table and the primary key of the tables that will be used to save the records of this content type. Tipically you will leave this set to the "records" table, using "id_record" as primary key.
If you need you are free to create more tables in addition to the records table. If you define stage table (attribute), the content type will use that table as staging for the records (that table needs also to include a column named "published" defined as INT(1) DEFAULT 0).

When you use external tables, Bancha can generate and keep updated these tables automatically for you (added in **v1.0.2**): click on the **Rebuild tables** link on the **Content list** view and you're done!

The **order_by** node, define the order of the records on the administration list views. By default, is set to **date_update** so the records are ordered by their last update date. You may change it to **date_update** if you are managing the posts of a blog, 'cos you can see the real order of the posts on the website.
**Note: you can order records only by a table physical column**.

The **categories** and **hierarchies** nodes says if content type has to show the Categories and Hierarchies sections. These sections makes you able to make sub-groups of records, such as the categories of a blog.


---------
Fieldsets
---------

A content type, is made by one or more :doc:`fieldsets/index`. Each fieldset, is made by :doc:`fieldsets/fields`.

.. toctree::
   :maxdepth: 1

   fieldsets/index
   fieldsets/fields

---------
Relations
---------

A content type can define relations between other records using the "1-0", "1-1" or "1-n" types.

.. toctree::
   :maxdepth: 1

   relations/define
   relations/use
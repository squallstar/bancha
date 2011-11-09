##########
XML Scheme
##########

Each content type is configured by a single XML file describing all its fields and is placed into **application/xml/types/** directory.
When you create a new content type, it's scheme file contains a simple xml structure with some "starters" fields.

The schema used for **tree-structured** content type definition is a bit different from the **simple-structured** one, since it includes more option for displaying custom content and some other things.
You can edit this file from the administration section (**Edit scheme** link on the types list), or by hand (the best choice) with your favorite editor. The basic structure of a content type is defined as follows::

    <?xml version="1.0" encoding="utf-8"?>
    <content id="1">
        <name>Pages</name>
        <descriptions label="Website pages" new="New page" />
        <tree>true</tree>
        <table key="id_record" production="records" stage="records_stage" />
        <fieldset name="Sample fields">
            <field id="first"></field>
            <field id="second"></field>
            <field id="third"></field>
        </fieldset>
    </content>
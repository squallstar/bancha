===========
Text fields
===========

These fields are used to store a single-line or multi-line text. It can be also formatted or not. Four different types are available as text fields:

* **Standard single-line text** (not formatted): "text"
* **Password text**: "password"
* **Multi-line** text (not formatted): "textarea_code"
* **Multi-line** text (formatted): "textarea"
* **Multi-line** full rich-text (CKEditor): "textarea_full"

The definition between these four types only changes on the **<type>** field. All the other nodes are equally available.
Below you can find a sample implementation about two differents text fields::

    <field id="post_title" column="false">
        <type>text</type>
        <description note="use up to 64 characters">Title of the post</description>
        <rules>max_length[64]</rules>
    </field>

::

    <field id="content" column="false">
        <type>textarea_full</type>
        <description>Post content</description>
        <rules>required</rules>
    </field>


Back to :doc:`fields`.
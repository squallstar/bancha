===========
File fields
===========

File fields are used to display an input "file" and let the users to upload one or more files linked to the record.

Think about a "Portfolio" content type: your records (projects), will likely have some images or files linked, and for this things you have to use this (or the image) field.

Simple usage::

    <field id="my_files">
        <description>Some files</description>
        <type>file</type>
        <size>4096</size>
        <mimes>zip|doc|docx|xls</mimes>
        <encrypt_name>true</encrypt_name>
        <max>3</max>
        <list>true</list>
    </field>

Note: documents are **not** always loaded on a record. To manually load the documents, use the **set_documents()** function such as the following example::

    $record->set_documents();

    //Gets the files
    $files = $record->get('my_files');

Back to :doc:`fields`.
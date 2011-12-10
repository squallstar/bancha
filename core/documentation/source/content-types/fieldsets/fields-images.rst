============
Image fields
============

Image fields are used to store one or more images on a record. A content type can use an infinite number of image fields, and each field can store many images.

In Bancha, any image is a "Document", which means that Bancha stores many info about the document (such as mime-type), not just the file.

An image field will always store the original image and each one can also be resized in two different sizes: "thumbnail" and "resized".

As resizing pattern, you can define a string with the desired width and height, or a question mark on one of these two as an "auto" placeholder, such as "640x?" or "?x300".

Simple usage::

    <field id="gallery">
        <description>Gallery images</description>
        <type>images</type>
        <size>4096</size>
        <mimes>jpg|gif|png|jpeg</mimes>
        <encrypt_name>true</encrypt_name>
        <original>true</original>
        <resized>640x?</resized>
        <thumbnail>60x?</thumbnail>
        <max>3</max>
        <list>true</list>
    </field>

The thumbnail will be also used as a preview of the image in the administration.

Later, you could also define many preset to apply to these files:
*** TODO**

Note: images (as the files fields) are **not** always loaded on a record. To manually load the documents, use the **set_documents()** function such as the following example::

    $record->set_documents();

    //Gets the files
    $files = $record->get('my_files');

Back to :doc:`fields`.
==============
Record Objects
==============

Record objects are extracted using the :doc:`../models/records` model. Please refer to the documentation to read more about extractions.

A Record is composed by some standard attributes such as its unique id and many protected data that can be retrieved using the **get** function::

    //Contains the record id
    $record->id

    //Contains the record content type id
    $record->_tipo

    //You can then retrieve the content type definition using the contents type() function
    $type_def = $this->content->type( $record->_tipo );

    //Other values (physical of xml) can be retrieved using the get function:
    $title = $record->get('title');


Below you can find a sample implementation of the :doc:`../models/records` model and record objects::

    $posts = find('Blog')->limit(10)->get();

    if (count($posts))
    {
    	foreach ($posts as $post)
    	{
    		echo $post->get('date_publish') . ': ' . $post->get('title') . "\r\n";
    	}
    }

To learn how to create or edit Records objects, read :doc:`record-create`.


----------------
Linked documents
----------------

If you need to manually extract the **documents** linked to a record, you can use its **set_document()** function::

    $record->set_documents();

    //Then, you can access the documents using the key defined into the xml scheme, such as this:
    $files = $record->get('files');
    $images = $record->get('images');

**Note:** if you are extracting many records, use the **documents** method of the :doc:`../models/records` model for better query performances::

    $posts = find('Blog')->documents(TRUE)->limit(10)->get();

    if (count($posts))
    {
    	foreach ($posts as $post)
    	{
    		print_r( $post->get('files') );
    	}
    }


---------------
Related records
---------------

If you set up a relation between two records (Read: :doc:`../../content-types/relations/define`) you can access the linked records using the **related()** function with the relation unique key::

    $comments = $post->related('comments');

More info: :doc:`../../content-types/relations/define` or :doc:`../../content-types/relations/use`.


----------------------
How to create reecords
----------------------

You can easily create, edit, publish, depublish and delete Record objects reading the following tutorial:

.. toctree::
   :maxdepth: 1

   record-create


Back to :doc:`../index`
=====
Pages
=====

Pages model are used to work with the pages of the website. Basically, when a **Record** of a **page structured** content type is saved, a **page** is also saved on the pages table. A page store the basically informations of a record, such the id and the title, plus the partial **slug** and the full url.

**NOTE: tipically you don't need to use this model because you can create the pages using the GUI from the Bancha administration**.

The slug of a page is called **uri**, and to manage the hierarchies of the pages you can link each other to its parent using the **id_parent** attribute (the id_record of the parent page).

You can use the pages model with the **records model** (that are auto-loaded by the framework) to create a page, such as this::

    //We create a record of type Menu (the default type used for the pages)
    $my_page = new Record('Menu');

    $my_page->set('title', 'My first page')
            ->set('uri', 'my-first-page');

    //We save the record
    $id = $this->records->save($my_page);

    if ($id) {
        //And we save the page
    	$this->pages->save($id);

    	//And we finally publish the page
    	$this->pages->publish($id);

    	//Now you can reach the page in this way: http://example.com/my-first-page
    }

To extract the pages, use the :doc:`tree` model.

Back to :doc:`../index`
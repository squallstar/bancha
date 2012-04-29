=========
JSON Feed
=========

JSON, or JavaScript Object Notation, is a lightweight text-based open standard designed for human-readable data interchange. It is derived from the JavaScript scripting language for representing simple data structures and associative arrays, called objects.

Each page that lists records can automatically render a JSON feed.

1. Create a page
2. On the "Actions" tab, set the action to "Content list"
3. Few lines below, be sure that the "Show feed" field is checked

Then, you can visit a page feed adding the "/feed.json" suffix to the page url, like this::

    //Page URL
    http://example.org/my-blog

    //RSS Feed
    http://example.org/my-blog/feed.json


To customize the fields that needs to be outputted, you can create a new file named **feed.php** inside the content type **type_templates** folder.

You can copy the default template here: **core/views/type_templates/Default-Content/feed.php**

**Note:** to enable/disable custom view files for the feeds, you can add the following variable to your website config file::

	// Inside application/config/website.php
	$config['type_custom_feeds'] = TRUE;

Back to :doc:`../index`.
============
XML-RSS Feed
============

Each page that lists records can automatically render an XML feed.

1. Create a page
2. On the "Actions" tab, set the action to "Content list"
3. Few lines below, be sure that the "Show feed" field is checked

Then, you can visit a page feed adding the "/feed.xml" suffix to the page url, like this::

    //Page URL
    http://example.org/my-blog

    //RSS Feed
    http://example.org/my-blog/feed.xml


To customize the feed appaerance, you can create a new file named **feed.php** inside the content type **type_templates** folder.

You can copy the default template here: **core/views/type_templates/Default-Content/feed.php**

**Note:** to enable/disable custom view files for the feeds, you can add the following variable to your website config file::

	// Inside application/config/website.php
	$config['type_custom_feeds'] = TRUE;

Back to :doc:`../index`.
=====================
Adding a new language
=====================

To add a language, first of all locate the **website_languages** under the **application/config/website.php** config variables, and add a new language as in the example below::

    $config['website_languages'] = array(

    	'en' => array(
    		'name'			=> 'english',
    		'locale'		=> 'en_US',
    		'description'	=> 'English',
    		'date_format'	=> 'Y-m-d'
    	),

    	//New languages goes here
    );

Now, duplicate one of the "website_homepage_xx" fields in the **application/xml/Settings.xml** scheme, using the language shortname, such as the **website_homepage_it** field.

Finally, create a page with that language and **publish it**, and go to the settings view to set it as the homepage for that language. You're done!

Next: :doc:`i18n`
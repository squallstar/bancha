#####################
Create our first page
#####################

By default, the pages of the website are linked to the content type named **Menu**.
You can change this behaviour adding an array of types to your config file **application/config/website.php**, listing one or more content type to use as website pages

    $config['default_tree_types'] = array('Menu');

Tipically you just need a single content type, so let's go on.

On the right upper side of the list you will see the **Create new page** button. Let's create a new page!

Type "My first page" as **Title** and paste some text to the **content textarea**, click **"Save and go to list"** and voilà: your first page is done!

To propagate the page that we just created on the production environment, **publish** it!
(if you are new to the stage-production environment, remember to read first :doc:`stage-workflow`)
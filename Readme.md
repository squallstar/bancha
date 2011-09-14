# BANCHA CMS

BANCHA is a Content Management System made with PHP5 and Code Igniter, a light and powerful PHP framework.
It is capable to manage medium and big projects/websites specially when they have many different types of contents.


BANCHA bases its power on some pillars that make it different from other CMS:
It allows you to handle any type of content: pages, news, photo galleries, products, etc ... through XML schemas.

 * It doesn't sacrifice the performance of a static site, because under the hood it uses many different caching systems.
 * It's modular, so it can be extended with different types of modules that you can develop yourself.
 * It's totally open-source (you can download the source from http://getbancha.com).
 * It's easy to install and to maintain. It doesnt't need any complicated or advanced infrastructure: just PHP5 and a DB server (or a SQLite one).
  * It totally separates the application framework (MVC) from the website themes, so it's easy to use for web developers as well as web designers.
  * It use a wonderful ORM system to "play" with the database objects without the needs to write a single query.

The **BANCHA documentation** is available through the BANCHA administration panel, under **Manage > Documentation**.

# How to install

1. Before all, sets your current environment in the /index.php file
    The default environment is "sqlite" (to use the Sqlite Database)
    but you are encouraged to use "development" or "production"

2. Sets the database configuration parameters here: application/config/database.php

3. Go through your browser to this URL: http://yourwebsitename/admin/install

4. Choose your install type between "Default" and "Blog". The Blog one, will create and configures the "Blog" and "Comments" content types for you.

4. When install is done, place a die(); at the start of this file: application/controllers/admin/install.php (alternately you can also delete it!)

5. You're done! The install script will create a content type named "Menu" which is linked to the page tree of your website.

# Resources

 * [Project homepage](https://github.com/squallstar/bancha)
 * [Open issues](https://github.com/squallstar/bancha/issues)

# Contribute via GitHub

To contribute through GitHub, first of all fork the main Bancha repository.
Then, checkout your new fork and type this line into the terminal to stay updated with the main repo:
 * git remote add upstream git://github.com/squallstar/bancha.git

Now you can pull the upstream updates anytime you want via these commands:
 * git fetch upstream
 * git merge upstream/master

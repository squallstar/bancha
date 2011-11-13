![Logo](http://static.squallstar.it/images/bancha-trasp.png)

# BANCHA - Open-source CMS

BANCHA is a Content Management System made with PHP5 and Code Igniter, a light and powerful PHP framework.
It is capable to manage any kind of project/website, specially when it have many different types of contents.

BANCHA bases its power on some pillars that make it different from other CMS:
It allows you to handle any type of content: pages, news, photo galleries, products, etc ... through XML schemas.
In 10 minutes, you can configure a website to manage, list and view any kind of thing you want.

 * It doesn't sacrifice the performance of a static site, because under the hood it uses many different caching systems.
 * It's modular, so it can be extended with different types of modules that you can develop yourself.
 * It's totally open-source (you can download the source from http://getbancha.com).
 * It's easy to install and to maintain. It doesnt't need any complicated or advanced infrastructure: just PHP5, some extensions and a DB server.
  * It totally separates the application framework (MVC) from the website themes, so it's easy to use for web developers as well as web designers.
  * It use a wonderful ORM system to "play" with the database objects without the needs to write a single query.

The **BANCHA documentation** is available through the BANCHA administration panel, under **Manage > Documentation**.

# How to install

1. Before all, choose your environment in the /index.php file
   The default environment is "development".

2. Now, configure your base settings here: application/config/config.php
   As you can see, this file is similar to the CodeIgniter config file.

3. Sets the database configuration parameters here: application/config/database.php

4. Go through your browser to this URL: http://yourwebsitename/admin/install

5. Choose your install type between "Default" and "Blog".
   The Blog one, will create and configures the "Blog" and "Comments" content types for you.

6. You're done! The install script will create a content type named "Menu" which is
   linked to the page tree of your website.

Need to reinstall? Just remove the **is_installed** row from the database table named **settings**.

# Application MVC Scheme

![MVC Scheme](http://static.squallstar.it/images/bancha_mvc_scheme.png)

# Changelog

See **core/documentation/source/basic/changelog.rst**

# Resources

 * [Project homepage](https://github.com/squallstar/bancha)
 * [Open issues](https://github.com/squallstar/bancha/issues)

# Contribute via GitHub

To contribute through GitHub, first of all fork the main Bancha repository.
Then, checkout your new fork and type this line into the terminal to stay updated with the main repo:

    git remote add upstream git://github.com/squallstar/bancha.git

Now you can pull the upstream updates anytime you want via these commands:

    git fetch upstream
    git merge upstream/master

NOTE: we are working hard to translate the documentation in English (currently it's just in Italian)

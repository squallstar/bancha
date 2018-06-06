![Logo](http://getbancha.com/attach/logos/logo.png)

# BANCHA - Open-source CMS

**NOTE: as you might have guessed after 4 years of inactivity, this project is no longer maintain although can still be used with virtually no issues.**

BANCHA is a Content Management System made with PHP5 and Code Igniter, a light and powerful PHP framework.
It is capable to manage any kind of project/website, specially when it have many different types of contents.

BANCHA bases its power on some pillars that make it different from other CMS:
It allows you to handle any type of content: pages, news, photo galleries, products, etc ... through XML or YAML schemes.
In 10 minutes, you can configure a website to manage, list and view any kind of thing you want.

 * It doesn't sacrifice the performance of a static site, because under the hood it uses many different caching systems.
 * It's modular, so it can be extended with different types of modules that you can develop yourself.
 * It's totally open-source (you can download the source from http://getbancha.com).
 * It's easy to install and to maintain. It doesnt't need any complicated or advanced infrastructure: just PHP5, some extensions and a DB server.
  * It totally separates the application framework (MVC) from the website themes, so it's easy to use for web developers as well as web designers.
  * It use a wonderful ORM system to "play" with the database objects without the needs to write a single query.

The **BANCHA documentation** is available here: **http://docs.getbancha.com**

# How to install

http://docs.getbancha.com/install/howtoinstall.html

# Bancha Bash Utilities

We also developed a **unix bash** script to help you installing/upgrading Bancha. You can install it in a minute here:
https://github.com/squallstar/bancha-bash

# Application MVC Scheme

![MVC Scheme](http://docs.getbancha.com/_images/mvc-scheme.png)

# Changelog

See **http://docs.getbancha.com/basic/changelog.html**

# Resources

 * [Official website](http://getbancha.com)
 * [Documentation](http://docs.getbancha.com)
 * [GitHub project homepage](https://github.com/squallstar/bancha)
 * [Open issues](https://github.com/squallstar/bancha/issues)

# Contribute via GitHub

To contribute through GitHub, first of all fork the main Bancha repository.
Then, checkout your new fork and type this line into the terminal to stay updated with the main repo:

    git remote add upstream git://github.com/squallstar/bancha.git

Now you can pull the upstream updates anytime you want via these commands:

    git fetch upstream
    git merge upstream/master

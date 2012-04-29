#################################
Upgrading From a Previous Version
#################################

==================
- Automatic update
==================

To update an existing installation of Bancha, you can simple use :doc:`bash` typing on the shell::

	bancha update

To install the bash utilities, see :doc:`bash`.


===============
- Manual update
===============

If you doesn't have shell access, you can do a manual update following this tiny tutorial.
Basically, all the Bancha framework is contained inside the **core** folder.

Beside, all the user content (tipically all the custom things of a website) is contained inside the **application** folder.
This means that to upgrade Bancha, you just need to replace the core folder with an updated one.
Finally, overwrite also the **themes/admin** folder:

1. Download the latest version of Bancha http://getbancha.com
2. Unzip the tarball
3. Replace the **core** folder
4. Replace the **themes/admin** folder
5. Log into Bancha and **Clear the cache**

You're done!

See also: :doc:`howtoinstall` or :doc:`/introduction/understanding`
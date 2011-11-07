##############
How to install
##############

Download Bancha from the official GitHub repository https://github.com/squallstar/bancha and unzip it into the root of your virtualhost.
Normally the index.php file will be at your root.

The base structure is composed as follows:

* .htaccess
* application/
* attach/
* documentation
* core/
* themes/
* index.php

For the best security, both the **core** and the **application** folders should be placed above web root so that they are not directly accessible via a browser. By default, .htaccess files are included in each folder to help prevent direct access.
If you move, the above two folders, be sure to update their paths on the **index.php** file (**$user_path** and **$core_folder** variables)

1. Open the application/config/config.php file and set your base URL. Please change also the encryption key to a random one.

2. Sets the database connection parameters here: application/config/database.php

3. Go through your browser to this URL: http://example.org/admin/install. If you see an error, check the previous steps!

4. Choose your install type between "Default" and "Blog". The Blog one, will create and configures the "Blog" and "Comments" content types for you.

6. You're done! The install script will create a content type named "Menu" which is linked to the page tree of your website.


Need to reinstall? Just remove the **is_installed** row from the database table named **settings**.
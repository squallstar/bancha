===============
Install a theme
===============

The process of installing an **existing theme** to a website made with Bancha is very simple.
First of all, locate the **themes directory** and **place the theme folder here.

By default, the Bancha theme directory is **/themes**.
Here you will find two pre-installed themes: **/themes/default** and **/themes/minimal**.

First of all, you have to locate and open with a text editor your website configuration file **application/config/website.php**.

On the first lines, you will find an array containing the list of available themes::

	$config['installed_themes'] = array(

		  'default'	=>	'Default theme'
		, 'minimal'	=>	'A minimal theme'
	);

To add a new theme, just type a new lines using the **folder name as key**, and a **short description** of the theme as **value**.
Now, you will need to activate the theme.


----------------
Activate a theme
----------------

To activate a theme, open the Bancha administration and go under the **Themes** page.
You will find a table with all the installed themes displayed and two columns for each row: **desktop** and **mobile**. Here you can define a **different theme** for each of these two user-agents.

After selecting a theme, clear the cache (**session+cookie**) of your browser or visit the **/go-desktop** (or /go-mobile) page from your browser and you're done!
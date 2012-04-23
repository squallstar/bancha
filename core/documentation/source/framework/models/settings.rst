========
Settings
========

Settings model is used to save and get any sort of string or number that needs to be a constant.
Available settings are defined throught the **application/xml/Settings.xml** scheme and can be extended such as the **Content types scheme** (:doc:`/content-types/scheme`). Oh, they just miss the **files**, **images** and **hierarchies** fields!

You can manage the settings under the **Manage >> Settings** view of the administration.

To get a setting, first of all you need to load the settings class (it's automatically loaded on the website+frontend so you can skip this part)::

	$this->load->settings();

Now you can read a setting using the **get** function in this simple way::

    $bar = $this->settings->get('foo');

    //Or, if you are in the theme of a website using the frontend helper:
    $bar = settings('foo');

As second parameter, the **get** fuction accepts the **namespace** of the settings: by default the website use as namespace the key **General**, you can use any other name for your custom settings::

    //Retrieve a setting from a custom namespace
    $bar = $this->settings->get('foo', 'mynamespace');


Likewise, to save a value on the disk, you can use the **set** function using the key as first parameter, the value as second parameter and the optional namespace as third one::

    $this->settings->set('foo', 'bar');

    //You can also save a setting on your custom namespace
    $this->settings->set('foo', 'barz', 'mynamespace');


After updating your values, be sure to clear che disk cache of the settings using the **clear_cache** function::

    //Clears che settings cache
    $this->settings->clear_cache();


To delete a key from the settings, use the **delete** function that accepts the same parameters as the **get** function::

    $this->settings->delete('foo');

    //Or from your custom namespace
    $this->settings->delete('foo', 'mynamespace');


Back to :doc:`../index`
===========
Controllers
===========

All Bancha controllers are located inside the **core/controllers** directory and have the **Core_** prefix.

You can extend any controller (website or administration) placing it in your corrispective **application** folder: **application/controllers**.

Take a look at the **Website** controller to know how to easily extend them, or follow this little example::

    require_once(APPPATH . 'controllers/admin/contents.php');

    Class Contents extends Core_Contents
    {
        public function foo()
        {
            parent::foo();

            //Do other things...
        }
    }


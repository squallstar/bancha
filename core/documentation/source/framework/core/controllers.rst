===========
Controllers
===========

This section shows you how to create new controllers or extend existing ones.

-----------------------
Create a new controller
-----------------------

There are no differences to the standard CodeIgniter documentation about creating controllers.
You will find an "Example" controller inside the application/controllers directory.

Basically, you just need to create a controller that extends the **Bancha_Controller** class::

    Class Something extends Bancha_Controller
    {
        public function index()
        {
            //Do some things...
        }
    }

And place it anywhere inside the application/controllers directory.


------------------------
Extend a Core Controller
------------------------

All Bancha controllers are located inside the **core/controllers** directory and have the **Core_** prefix.

You can extend any controller (website or administration) placing it in your corrispective **application** folder: **application/controllers**.

Take a look at the application **Website** controller to know how to extend them, or follow this little example::

    require_once(APPPATH . 'controllers/admin/contents.php');

    Class Contents extends Core_Contents
    {
        public function foo()
        {
            parent::foo();

            //Do other things...
        }
    }


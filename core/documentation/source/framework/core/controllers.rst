===========
Controllers
===========

-----------------------
Create a new controller
-----------------------

There are no difference to the standard CodeIgniter documentation.
You will find an "Example" controller inside the application/controllers directory.

Basically, you just need to create a controller that extends the **Bancha_Controller** class::

    Class Something extends Bancha_Controller
    {
        public function index()
        {
            //Do some things...
        }
    }


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


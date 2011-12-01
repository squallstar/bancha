==================
Create a new theme
==================

To create a new theme, you can duplicate one of the default themes shipped with Bancha (default and minimal) or you can make a new theme from scratch.

This tutorial helps you creating a theme from scratch, so you can fully understand all the components of a theme.

To start, make a new folder inside the **/themes** directory. Inside it, create these directories: **css**, **js**, **views**, **images**. Inside the **views** directory, create also two directories: **templates** and **type_templates**. The structure will looks like this::

    themes/themename/css
    themes/themename/js
    themes/themename/images
    themes/themename/views/templates
    themes/themename/views/type_templates

Note: only the **views** directory and its sub-folders are required.

-----
Views
-----

Let's take a short tour of the **views** folder before starting:

* The **views** folder contains the base layout files, such as the header, the footer and the content render.
* The **views/templates** directory will contains all the **page templates** that Bancha uses during the rendering process.
* Finally, the **views/type_templates** directory will contains all the custom content types rendering templates.

Ok, let's start from the first point: the views folder.
Create these files in the views directory::

    1) themes/themename/views/layout.php
    2) themes/themename/views/header.php
    3) themes/themename/views/footer.php
    4) themes/themename/views/content_render.php

Before starting, take a look at this little thing. The view cycle, looks like this:
**Layout** >> **Template** >> **Content render**

Basically, the layout file loads the template of the page, and the template of the page loads the content render.

^^^^^^^^^
1) Layout
^^^^^^^^^

The base layout of a theme is included into the **views/layout.php** file.
Below, you can see a simple implementation of that file::

    <html>
        <head>
            <title><?php echo $this->view->title; ?></title>
            <meta name="description" content="<?php echo $this->view->description; ?>">
            <meta name="keywords" content="<?php echo $this->view->keywords; ?>">
            <?php echo echo link_tag(theme_url('css/style.css')); ?>
        </head>
        <body>

            <div id="wrapper">
            <?php $this->view->render($_template_file); ?>
            </div>

            <script src="<?php echo theme_url('js/application.js');?>"></script>

        </body>
    </html>

As you can see, the scope of this file is to declare the base html, head, etc.. of the page, plus loading the **$_template_file** inside a wrapper. The templates are located under the **views/templates** folder and we are gonna see them in few minutes.


^^^^^^^^^^^^^^^^^
2) Content render
^^^^^^^^^^^^^^^^^

**Note: this file is not mandatory, the framework will use the default one located in core/views/content_render.php when a custom content render is not defined in the theme.**
If you choose to not extend this file, skip this section.

This file is responsable of handling the actions of a page. On the :doc:`/firststeps/index` tutorial under the section :doc:`/firststeps/content-render` you have learned how each page can choose its behaviour. Now it's time to implement those behaviours on the theme throught the **content_render.php** file.

If you had trouble reading this, please read :doc:`/firststeps/content-render`.
As you can see opening the **core/views/content_render.php** file, basically there's a switch that tests which **action** the page wants to be fired.
When the case is **single** or **list**, the **views/type_templates** will be used.

**Note:** between the **layout** and the **content_render**, there's the **template** (next section).
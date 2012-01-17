==================
Create a new theme
==================

To create a new theme, you can duplicate the default theme shipped with Bancha (**sandbox**) or you can make a new theme from scratch.

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
            <title><?php echo title(); ?></title>
            <meta name="description" content="<?php echo page_description(); ?>">
            <meta name="keywords" content="<?php echo page_description(); ?>">
            <?php echo echo link_tag(theme_url('css/style.css')); ?>
        </head>
        <body>

            <div id="wrapper">
            <?php template(); ?>
            </div>

            <script src="<?php echo theme_url('js/application.js');?>"></script>

        </body>
    </html>

As you can see, the scope of this file is to declare the base html, head, etc.. of the page, plus loading the **$template** inside a wrapper. The templates are located under the **views/templates** folder and we are gonna see them in few minutes.


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


^^^^^^^^^^^^
3) Templates
^^^^^^^^^^^^

Each Page record has a **view_template** attribute that decide which template should be rendered for that page.
Templates are contained inside the folder **views/templates**.

Take a look at the following example that implements a basic template::

    render('header');

    echo page('title');
    echo '<p>' . page('content') . '</p>';

    render('footer');


The **sandbox** theme ships with two templates: the **home.php** template and the **default.php** template.
This second one, is the heart of the theme because it calls the Content render::

    render('header');

    content_render();

    render('footer');

To add a new template, first of all you need to create the template file inside your theme, for example **views/templates/my_template.php**.
After adding a new template, you need to add that template also to the **view_template field** of the content types (tipically just the "Menu" only). On the XML Scheme, edit the field as follows::

    <field id="view_template"><description>View template</description>
        <type>select</type>
        <options>
            <option value="default">Default</option>
            <option value="homepage">Homepage</option>
            <option value="my_template">My template</option>
        </options>
    </field>


Then, when you create a page you can also select that template from the **View template** dropdown menu of the **Aspect** section.


^^^^^^^^^^^^^^^^^^^^^^^^^
4) Content-Type templates
^^^^^^^^^^^^^^^^^^^^^^^^^

In Bancha, each theme can define custom templates for every content type. Each content type can have two different views: **list** and **detail**.
If you are creating a new theme, you need to define at least two content type templates: **Default-Content** and **Default-Pages**. They are used to render the content types if a better choice is not available.

Example: if your page is listing articles of type **Blog**, Bancha will look for this file inside this theme::

    themes/sandbox/views/type_templates/Blog/list.php

If that file does not exists, the default one will be used::

    themes/sandbox/views/type_templates/Default-Content/list.php


So, be sure to create the **Default-Content** and **Default-Pages** folders!

More informations are available on the **Content list** section of the :doc:`/firststeps/content-render`.
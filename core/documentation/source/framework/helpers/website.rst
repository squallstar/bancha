==============
Website helper
==============

This helper is automatically loaded by the framework and contains many useful functions for the front-end of the website.
The helper is located here: **core/helpers/website_helper.php**


---------------
Debug an object
---------------

**debug( $str )**

Simply prints out the content of an object.
Usage::

    $str = 'hello';
    debug($str);


------------------
Forbidden redirect
------------------

**show_400( )**

Shows a 400 (Forbidden) error page.::

    //You have no rights to access this page
    show_400();


-----------
Website url
-----------

**site_url( [$str='' , $lang ] )**

Builds the URL of the website appending the provided optional string.
The second parameter (true by default) enable or disable the language prefix.
Usage::

    echo site_url();
    //Displays: http://example.org

    echo site_url('blog');
    //Displays: http://example.org/en/blog

    echo site_url('blog', FALSE);
    //Displays: http://example.org/blog

---------
Admin url
---------

**admin_url( [$str=''] )**

Generates the URL of the administration, appending the provided optional string.
You can change your administration URL by the **$admin_path** variable on the **/index.php** file.
Usage::

    echo admin_url();
    //Displays: http://example.org/admin

    echo admin_url('dashboard');
    //Displays: http://example.org/admin/dashboard


-----------------
Current theme url
-----------------

**theme_url( [$str=''] )**

Generates the URL of the current theme, appending the provided optional string.
Usage::

    echo theme_url();
    //Displays: http://example.org/themes/default/

    echo theme_url('js/application.js');
    //Displays: http://example.org/themes/default/js/application.js


-----------------
Attachment url
-----------------

**attach_url( $str )**

Returns the public url of an attachment. Tipically you use this passing the path of an Image (or file) object of a Record.
Usage::

    $images = $record->get('images');
    
    echo attach_url($images[0]->path);
    //Displays: http://example.org/attach/Blog/1/imagename.jpg


-----------------
Preset url
-----------------

**preset_url( $path, $preset [, $append_siteurl ] )**

Returns the path of an image preset, given the path and the preset name to apply.
Image presets are configured inside the **application/config/image_presets.php** file.
Presets are cached inside the **/attach/cache** folder: to clear the cache, just remove the sub-directories in that folder.
Usage::

    $images = $record->get('images');
    
    echo preset_url($images[0]->path, 'user-profile');
    //Displays: http://example.org/attach/cache/Blog/1/user-profile/imagename.jpg


----------------
Minify resources
----------------

**minify( $files_array [, $version] )**

Returns the path of a minified file generated using the the **provided resources** (the path must be **relative to the current theme**. Bancha has an internal minifing system that you can use to **merge** and **minify** css and javascript files.
These files are tipically stored inside the **/attach/cache/resources-css** and **/attach/cache/resources-js** folders. Delete these folders to clear che resources cache.
Usage::

    //Minifies two Javascript files
    echo '<script src="' . minify(array('js/jquery.js', 'js/app.js')) . '"></script>';

    //Minifies two CSS files
    echo link_tag( minify(array('css/reset.css', 'css/style.css'), 1) );


-------------------
Semantic detail url
-------------------

**semantic_url( $record )**

This function tries to generate the detail url of the given **Record**.
The param must be a **Record object**, or the **slug to append**.
Usage::

    echo semantic_url($post);
    //Displays: http://example.org/blog/my-first-post

    /* Another example:
     * After extracting some products, we displays their detail links
     */
    $products = $this->records->type('Products')->limit(10)->get();

    foreach ($products as $product) {
        echo '<a href="' . semantic_url($product) . '">' . $product->get('title') . '</a>';
    }


--------------------------------------
Getting the content of an external url
--------------------------------------

**getter( $url )**

Makes a simple GET cURL call to an external webservice. Usage::

    echo getter('http://www.google.it');
=============
Image presets
=============

Bancha includes a wonderful system to perform batch operations to images. A collection of one or more procedural operations is called **preset**.

When a preset is applied to an image, each operations will be performed and the final output will be saved to disk. This file will be finally used for all the successive requests.

Presets are defined in the file **application/config/image_presets.php** and they consists in simple PHP associative arrays.

Let's look at an example of preset::

    $config['presets']['my-preset'] = array(
        array(
            'operation' => 'resize',
            'size' => '640x?',
            'ratio' => TRUE,
            'quality' => 70
        )
    );

The above preset, do a resize on a single file: the final image, will have a width of 640 pixels and the proportionally-scaled height.

If you image have this sample url::

    http://localhost/attach/blog/images/2/my_image.jpg

To use the preset that we just defined above, you need to append the "cache" directory and the preset name to the url, such as this::

    http://localhost/attach/cache/blog/images/2/my-preset/my_image.jpg

To automatically generate a preset url, use the **preset_url** helper documented here below.


-----------------
Preset URL helper
-----------------

**preset_url( $path, $preset [, $append_siteurl ] )**

Defined in :doc:`/framework/helpers/website`, returns the path of an image preset, given the path and the preset name to apply.
Presets are cached inside the **/attach/cache** folder: to clear the cache, just remove the sub-directories in that folder.
Usage::

    $images = $record->get('images');
    
    echo preset_url($images[0]->path, 'user-profile');
    //Displays: http://example.org/attach/cache/Blog/1/user-profile/imagename.jpg
    
    //The original img url was:
    //http://example.org/attach/Blog/1/imagename.jpg


------------------------
Multi-operations presets
------------------------

A single preset can have more than one operation. The operations will be performed from the top to the bottom.::

    $config['presets']['user_profile'] = array(
        array(
            'operation' => 'resize',
            'size' => '150x150',
            'fixed' => TRUE,
            'quality' => 100,
            'ratio' => TRUE
        ),
        array(
            'operation' => 'crop',
            'size' => '125x125',
            'quality' => 80,
            'x' => 25,
            'y' => 25
        )
    );

-------------
Presets cache
-------------

Preset images are cached on the filesystem after the first request. You can clear the presets cache by removing the **attach/cache** folder (or one of its sub-directories).


--------------------
Available operations
--------------------

1. **resize** - to resize images
2. **crop** - to crop/cut images


^^^^^^^^^
1. Resize
^^^^^^^^^

Available options are:

* **size** - (string) - to define the resize dimensions (eg. 150x150, 400x?, ?x320)
* **fixed** - (bool) to specify if both dimensions should be at least equals or higher than the defined ones
* **quality** - (int 0-100) to select the saving JPEG quality of the generated preset
* **ratio** - (bool) - to define if the proportions should be respected (scale versus stretch)

Example of usage::

    $config['presets']['standard'] = array(
        array(
            'operation' => 'resize',
            'size' => '640x?',
            'ratio' => TRUE,
            'quality' => 70
        )
    );


^^^^^^^
2. Crop
^^^^^^^

Available options are:

* **size** - (string) - to define the crop size (eg. 150x150, 640x480)
* **quality** - (int 0-100) to select the saving JPEG quality of the generated preset
* **x** - (int) to define the origin of crop on the x-axis
* **y** - (int) to define the origin of crop on the y-axis

Example of usage::

    $config['presets']['user_profile'] = array(
        array(
            'operation' => 'crop',
            'size' => '125x200',
            'quality' => 80,
            'x' => 20,
            'y' => 20
        )
    );
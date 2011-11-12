##################
The content render
##################

On the previous chapter, you learned how to set up a page to list Blog posts.
The **content render** is the element responsable of the rendering of the body of the pages/records.

It fires different actions based on the behaviour that each page choose.

* Single text
* Content list
* External link
* Custom action

The **content render** is tipically placed in the container element of a website, and is defined inside every theme: this means that each teme can completely customize how the **content render** takes care of any action!

After reading this, **jump to** :doc:`/content-types/index`.

-----------
Single text
-----------
When a page choose the action **Single text**, the **Content render** just displays the text you typed into the field named **content**.


------------
Content list
------------

If a page is set to the **Content list** action, first of all the **Dispatcher** extracts the record list, and links it to the **page**.
Then, when the **content render** is called it will load the **custom template** of the content type passing the record list as parameter.

In our example, these are the operations performed under the hood:

1. The router tells the dispatcher to extract the **my-first-page** page.
2. The dispatcher see that the page action is **Content list**, so extracts the **posts of the blog**.
3. The dispatcher links the posts to the page, and launch the **view** rendering process.
4. While in the view, the **content render** see that there is a record list, so it checks the type of the records (in our example: Blog).
5. The content render loads the template view of that content type, using the current theme: **themes/default/views/type_templates/Blog/list.php**.
6. Finally, the view will be rendered and passed back as output.

When you click on a record (a post), the differences are that only the chosen record will be extracted, and the rendered view will be **Blog/detail.php** instead of **Blog/list.php**.


-------------
External link
-------------

When a page is set to the **External link** action, when you reach that page you will simply "301" redirected to the chosen page. Use it to points a page of your website to another page or just any link outside your website.


-------------
Custom action
-------------

Bancha can let a page the permission to call a pre-made PHP method inside the framework. When choosing the **custom action**, a field will appear asking you the **name of the action**. This must be the name of your custom method (a function) defined inside the **Actions controller** which is located in **application/controllers/custom/actions.php**.

You just need to add a method to that class, and will be called by the page. Feel free to "echoing" any output using the **view system** while in that method.

Custom methods will also receive the caller as first parameter, so the function can always know who called it.

::

    Class Actions extends Core_Actions
    {
        public function myAction($caller)
        {
             echo $caller->get('title') . ' called me!';
        }
    }

^^^^^^^^^^^
Action mode
^^^^^^^^^^^

You can also select if you prefer that your action will be called in the **Dispatcher** or in the **Content render**. The difference is that the **Dispatcher** is the first thing called after the routing system, so the action will be called prior to the view rendering process.

Otherwise, choosing **Content render** as option will call your custom method inside the **Content render**, that is placed just inside the view container. You could select this option in many situations that requires the rendering to the sent exactly in the content space of the page (instead of displaying the page content - eg. a custom a form).
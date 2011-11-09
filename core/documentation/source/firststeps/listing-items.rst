#############
Listing items
#############

Every page in Bancha, can be set to fire different actions. From a single static text to a dynamic list of records.

Come back to the **Menu** content types, and edit (or create) a page.
On the top sidebar, you will see the **"Actions"** section.

Here, you can define the behaviour of the page between these choices:

* Single text
* Content list
* External link
* Custom action

Let's focus for a moment on the **"Content list"**.
When you choose this option, many other fields appears.
The first new field is **Content type**: here, you have to select the source content type of your list.
In our tutorial, this will likely be **Blog** because we want to list the blog posts.

Choose it, and save our page... hooray! It's time for fun! Switch to the front-end (the **Back to site** link on the bottom left of Bancha) and visit the page you just created.

You should see the list of the records of type **Blog**! Clicking on an item will send you to the detail of that record. As you can see, the URL will be the same plus the blog post URI::

    //Page url:
    http://example.org/my-first-page

    //Blog post detail:
    http://example.org/my-first-page/title-of-my-post

When the last segment of a URL isn't a page, the Bancha **Default Dispatcher** will find a page using the previous segment and checks whether that page is listing contents of the same type of the last segment. Pretty complicated to understand, but don't worry because you don't have to learn all this things right now!

On the next chapters you will learn how to completely customize the layout of that lists/details pages, and which field need to be shown.

Now it's time to discover how the **Content render** works: :doc:`content-render`
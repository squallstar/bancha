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
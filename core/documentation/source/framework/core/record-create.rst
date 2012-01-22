---------------------
Create Record objects
---------------------

To create a Record, you can make a new instance of the **Record class** passing the content-type as first parameter as follows::

    $post = new Record('Blog');

    $page = new Record('Menu');

    $comment = new Record('Comments');


Then, you can set the values to the record using the **set** instance method::

    $post = new Record('Blog');

    $post->set('title', 'My first post')->set('author', 'Nicholas');


Finally, to **save a record** you can use the **save()** function, or you pass it to the :doc:`/framework/models/records` model (both solutions are equals)::

    $done = $post->save();


Let's look at a further example. Here we create a new blog post and we link to that post a comment::

    $post = new Record('Blog');
    $post->set('title', 'My second post')->set('date_publish', time());

    $done = $post->save();

    if ($done)
    {
    	$comment = new Record('Comments');
    	$comment->set('author', 'Nicholas')->set('content', 'Hello!')->set('post_id', $post->id);
    	$comment->save();

    	//We can also publish both records
    	$post->publish();
    	$comment->publish();
    }


Finally, we delete all the things we created::

    $post->delete_related('comments');
    $post->delete();


See also: :doc:`/framework/models/records` model.

Back to :doc:`record`
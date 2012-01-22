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


Finally, to **save a record** you can pass it to the :doc:`/framework/models/records` model::

    $pKey = $this->records->save($post, 'Blog');

    $pKey = $this->records->save($comment, 'Comments');


Let's look at a further example. Here we create a new blog post and we link to that post a comment::

    $post = new Record('Blog');
    $post->set('title', 'My second post')->set('date_publish', time());

    $pKey = $this->records->save($post, 'Blog');

    if ($pKey)
    {
    	$comment = new Record('Comments');
    	$comment->set('author', 'Nicholas')->set('content', 'Hello!')->set('post_id', $pKey);

    	$pKeyComment = $this->records->save($comment, 'Comments');

    	//We can also publish both records
    	$this->records->publish($pKey, 'Blog');
    	$this->records->publish($pKey, 'Comments');
    }


Finally, we delete all the things we created::

    $comments = $post->related('comments');

    $this->records->delete_by_id($post->id, 'Blog');

    if (count($comments))
    {
    	foreach ($comments as $comment)
    	{
    		$this->records->delete_by_id($comment->id, 'Comments');
    	}
    }

See also: :doc:`/framework/models/records` model.

Back to :doc:`record`
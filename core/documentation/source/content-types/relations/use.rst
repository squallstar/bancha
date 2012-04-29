################
Using a relation
################

After setting up a relation (:doc:`define`), you can easily get the related records of a record in this simple way::

    $record->related('relation_name');

* On a **1-0 or 1-1 relation**, you will get a single Record object.
* Otherwise, on the **1-n relations** you will get an array of Record objects.

Set up the following relation on the "Blog" content type::

	relations :
		post_comments :
			type : 1-n
			with : Comments
			from : id_record
			to   : post_id

or using XML::

    <relation name="post_comments" type="1-n" with="Comments" from="id_record" to="post_id" />

Then, access the comments of a post like this::

    $comments = $post->related('post_comments');

More informations: :doc:`define`.

Back to :doc:`/content-types/scheme`.
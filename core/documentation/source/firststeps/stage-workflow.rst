##################
The stage workflow
##################

Bancha is capable of using two different environments in the same website: stage and production. This means that you can different pages/records from the stage to the production environment. By default, when you create a page (or a content) this will be saved on the stage tables, and the record will be marked as **Draft**.

-----------------------------
Stage-production environments
-----------------------------

To propagate the record on the production environment, you need to **publish** it!
The page record will be copied to the production tables and marked as **published** so the users that navigate the website can see it.

If you edit a published page/content, the state will be placed to **different** and it means that the stage and the production versions are different! While a record stills in this state, you can publish it (stage overwrite the production), or discard the stage record (production record will be copied to stage).

When you're logged into Bancha, on the website front-end you will navigate using the stage records. Likewise, when a guest visit your website will use only the production records.
This means that you can prepare tens of "hidden" pages/records, and publish all of them when you're ready!

Well, proceed to the next chapter: :doc:`create-pages`
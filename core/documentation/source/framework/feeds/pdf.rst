========
PDF Feed
========

This is not properly a feed. Instead, is based on the **Print dispatcher** (:doc:`/framework/core/dispatchers`) that converts any of the website pages to PDF file. Is automatically used by the **default dispatcher** when an URL is appended with **/print.pdf** after the page URL.

To works, needs the **DOMPDF library** that is not included by default (is large and un-necessary for the most of the websites).
In order to use the dispatcher, you need to download the library `here <http://code.google.com/p/dompdf/>`_.
Then, place the library under **core/libraries/externals/dompdf** and you're done::

    //Standard page:
    http://example.org/my-first-page

    //PDF page:
    http://example.org/my-first-page/print.pdf

You can manually load the dispatcher to use it as you want in this way::

    $this->load->dispatcher('print');
    $this->dispatcher->render($page);

Back to :doc:`../index`.
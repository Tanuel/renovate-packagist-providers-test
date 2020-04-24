GOOGLE ANALYTICS README

In order to run, this extension needs an active Google Analytics account, 
and an existing site setup within GA.

In order to work with your GA setup you need to add the following to the
template constants or setup (either way works):

plugin.tx_googleanalytics_pi1.uacct = UA-XXXX-X

That is, you don't need to paste all the code from Google, just the UA-code
specified in the Google code (the value inside the quotes, after '_uacct =').

Enabling the extension will set the TS Setup 'page.1000' to include the GA
code on the page. 

If your page object is named differently or you already use a page.1000 object, 
you'll need to add the following to your Template setup:

[mypageobject].1111 < plugin.tx_googleanalytics_pi1

...where [mypageobject] should be substituted with your own page object.

If all is well, the Google JavaScript will be included after the TITLE and META
sections in the HEAD section, and a HTML comment stating that the plugin was 
included will be added just above '</body>' on all pages.
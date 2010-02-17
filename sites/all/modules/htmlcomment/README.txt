HTMLCOMMENT
===========

HTMLcomment provides an input format filter that allows HTML comments,
i.e. <!-- … -->, and an additional filter that allows hidden comment,
i.e. [!-- … --].

Author:
  Thomas Barregren <http://drupal.org/user/16678>.

Sponsor:
  NodeOne <http://nodeone.se>.


BACKGROUND
----------

An HTML comment is a text surrounded with <!-- and -->. If you put a such
comment in a text area, you expect it to be in the HTML code, but not rendered
on the screen. Unfortunately, Drupal converts < and > to their HTML entities,
resulting in the display of the comment. This module provides a input format
filter that solves this problem.

In addition, this module also provides a filter that removes any text
surrounded with [!-- and --]. The purpose is to allow private comments in text
areas.

Example of an HTML comment:

    <!-- This will be in the HTML, but not displayed. -->

Example of a private comment:

    [!-- This will not be in the HTML, and not displayed. --]


INSTALL
-------

See http://drupal.org/node/70151.


CONFIGURATION
-------------

To enable the filters provided by the module for a particular input format,
do as follows:

1. Go to admin/settings/filters.

2. Click on the configure link of the input format.

3. To enable the HTML comment filter, check "HTML comments".

4. To enable the private comment filter, check "Private comments".

5. Click on the "Save configuration" button.

6. Click on the "Rearrange" tab.

7. Rearrange the filters such that "HTML comments" is executed after "HTML
   corrector". That is, the weight of "HTML corrector" should be less that the
   weight of "HTML comments". (This is not necessary for "Private comments".)


USAGE
-----

To make a comment that should be <em>visible</em> in the HTML, surround it
with <!-- and -->, e.g. <!-- This is a HTML comment -->.

To make a comment that should be <em>not visible</em> in the HTML, surround it
with [!-- and --], e.g. [!-- This is a private comment --].


$Id: README.txt,v 1.1.2.1 2009/05/16 19:20:35 tbarregren Exp $

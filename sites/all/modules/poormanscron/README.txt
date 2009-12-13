$Id: README.txt,v 1.5.2.1 2009/05/06 05:21:32 robloach Exp $

Description
-----------

Poormanscron is a module which runs the Drupal cron operations without
needing the cron application.

For every page view, this module checks to see if our last cron run was more
than 1 hour ago (this period is configurable). If so, the cron hooks are
executed (which, for example, update RSS/syndication blocks), and Drupal
is happy. These cron hooks fire after all HTML is returned to the browser,
so the user who kicks off the cron jobs should not notice any delay.


Requirements
------------

This module requires Drupal 4.7 or a later version.


Installation
------------

1) Extract the package.
2) Copy/upload the Poormanscron folder to your sites/all/modules directory of
   your Drupal installation (e.g. sites/all/modules/poormanscron).
3) Enable the Poormanscron module in Drupal (administer -> modules).


Configuration
-------------

Poormanscron can be configured at:
  Administer -> Site configuration -> Poormanscron


Authors
-------

 * Moshe Weitzman <weitzman@tejasa.com> - original author
 * Uwe Hermann <uwe@hermann-uwe.de> - current maintainer


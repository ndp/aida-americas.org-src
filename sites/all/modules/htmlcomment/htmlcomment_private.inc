<?php

// $Id: htmlcomment_private.inc,v 1.1 2009/05/16 18:59:22 tbarregren Exp $


/**
 * Author:
 *   Thomas Barregren <http://drupal.org/user/16678>.
 */


/**
 * Returns a description of the private comment filter
 */
function _htmlcomment_filter_private_description() {
  return t('Allows private comments within [!-- … --] that will be <em>not visible</em> in the HTML.');
}


/**
 * Returns the short filter tips.
 */
function _htmlcomment_filter_private_short_tips() {
  return t('You may put comments within [!-- and --]. These comments will be <em>not visible</em> in the HTML.');
}


/**
 * Returns the long filter tips.
 */
function _htmlcomment_filter_private_long_tips() {
  return t('To make a comment that should be <em>not visible</em> in the HTML, surround it with [!-- and --], e.g. [!-- This is a private comment --].');
}


/**
 * Removes all private comments from the text.
 */
function _htmlcomment_filter_private_process($text) {
  return preg_replace('/\[!--(.*?)--\]/s', '', $text);
}


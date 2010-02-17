<?php

// $Id: htmlcomment_html.inc,v 1.1 2009/05/16 18:59:22 tbarregren Exp $


/**
 * Author:
 *   Thomas Barregren <http://drupal.org/user/16678>.
 */


/**
 * Returns a description of the HTML comment filter
 */
function _htmlcomment_filter_html_description() {
  return t('Allows HTML comments within &lt;!-- … --&gt; that will be <em>visible</em> in the HTML.');
}


/**
 * Returns the short filter tips.
 */
function _htmlcomment_filter_html_short_tips() {
  return t('You may put comments within &lt;!-- and --&gt;. These comments will be <em>visible</em> in the HTML.');
}


/**
 * Returns the long filter tips.
 */
function _htmlcomment_filter_html_long_tips() {
  return t('To make a comment that should be <em>visible</em> in the HTML, surround it with &lt;!-- and --&gt;, e.g. &lt;!-- This is a HTML comment --&gt;.');
}


/**
 * Protect <!-- ... --> from other filters.
 */
function _htmlcomment_filter_html_prepare($text) {
  return preg_replace('/<!--(.*?)-->/se', "_htmlcomment_filter_html_prepare_callback('$1')", $text);
}


/**
 * Restore <!-- ... -->.
 */
function _htmlcomment_filter_html_process($text) {
  return preg_replace('/\[htmlcomment\](.*?)\[\/htmlcomment\]/se', "_htmlcomment_filter_html_process_callback('$1')", $text);
}


/**
 * Callback function of preg_replace() in _htmlcomment_filter_html_prepare().
 */
function _htmlcomment_filter_html_prepare_callback($text) {

  // Remove \ that " is escaped with when the pattern modifier e.
  $text = str_replace('\"', '"', $text);

  // Replace line feeds with its HTML entity to protect it from the
  // Line break converter.
  $text = str_replace(array("\r", "\n"), array('', '&#10;'), $text);

  // Replace <!-- ... --> with [htmlcomment] and [/htmlcomment], respectively,
  // to protect them from the HTML corrector and HTML filter.
  $text = '[htmlcomment]' . $text . '[/htmlcomment]';

  return $text;

}


/**
 * Callback function of preg_replace() in _htmlcomment_filter_html_process().
 */
function _htmlcomment_filter_html_process_callback($text) {

  // Remove \ that " is escaped with when the pattern modifier e.
  $text = str_replace('\"', '"', $text);
  
  // Restore line feeds.
  $text = str_replace('&#10;', "\n", $text);  
  
  // Restore the HTML comments.
  $text = '<!--' . $text . '-->';

  return $text;

}


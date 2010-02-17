<?php
// $Id: template.php,v 1.1 $
// Created for AIDA by Buttonwillow Six
// www.buttonwillowsix.com 
// based on Beginning  by gazwal.com
/**
 * Override or insert PHPTemplate variables into the templates .
 */
function phptemplate_preprocess_page(&$vars) {
	// set variables for the logo and slogan (from Deco drupal 6.x theme)
	$site_fields = array();
  if ($vars['site_name']) {
    $site_fields[] = check_plain($vars['site_name']);
  }
  if ($vars['site_slogan']) {
    $site_fields[] = check_plain($vars['site_slogan']);
  }
  
	$vars['site_title'] = implode(' ', $site_fields);

	if (isset($site_fields[0])) {
  	$site_fields[0] = '<span class="site-name">'. $site_fields[0] .'</span>';
	}
	if (isset($site_fields[1])) {
		$site_fields[1] = '<span class="site-slogan">'. $site_fields[1] .'</span>';
	}
	
  $vars['site_title_html'] = implode(' ', $site_fields);


//BW6 Make template suggestions for pages to look for template files, for example
//page-faq.tpl.php
//used for custom project pages at the moment

  if (module_exists('path')) {
    $alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
    if ($alias != $_GET['q']) {
      $template_filename = 'page';
      foreach (explode('/', $alias) as $path_part) {
        $template_filename = $template_filename . '-' . $path_part;
        $vars['template_files'][] = $template_filename;
      }
    }
  }

//Not implemented Now
//Get class for under construction page based on taxonomy term
//http://adaptivethemes.com/give-your-nodes-some-class
// first check to see if there are any terms at all, no point running this if not.
  if ($vars['node']->taxonomy) {

    // declare a variable
    $node_term_classes = 'Finalized';
 
    // check if the node has the term with the id of 64 attached.
    if ($vars['node']->taxonomy[64]) {

      // if so print a class, notice the safe_string function is cleaning up the term.
      $node_term_classes == 'Under Construction';

    }

  }//End tax term idea from http://adaptivethemes.com/give-your-nodes-some-class


}

/**
 * Allow themable wrapping of all comments (from garland).
 */
function phptemplate_comment_wrapper($content, $node) {
  if (!$content || $node->type == 'forum') {
    return '<div id="comments">'. $content .'</div>';
  }
  else {
    return '<div id="comments"><h2 class="comments">'. t('Comments :') .'</h2>'. $content .'</div>';
  }
}

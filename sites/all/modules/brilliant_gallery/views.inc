<?php
// $Id: views.inc,v 1.2.2.1 2009/01/07 23:56:53 tjfulopp Exp $


/* TODO FormAPI image buttons are now supported.
   FormAPI now offers the 'image_button' element type, allowing developers to
   use icons or other custom images in place of traditional HTML submit buttons.

$form['my_image_button'] = array(
  '#type'         => 'image_button',
  '#title'        => t('My button'),
  '#return_value' => 'my_data',
  '#src'          => 'my/image/path.jpg',
); */

/* TODO Remove $row argument from db_result() method
   The $row argument of db_result() was removed from the database abstraction
   layer in 6.x core, as it was a database dependent option. Developers need to
   use other handling to replace the needs of this method. */

/* TODO New user_mail_tokens() method may be useful.
   user.module now provides a user_mail_tokens() function to return an array
   of the tokens available for the email notification messages it sends when
   accounts are created, activated, blocked, etc. Contributed modules that
   wish to make use of the same tokens for their own needs are encouraged
   to use this function. */

/* TODO
   There is a new hook_watchdog in core. This means that contributed modules
   can implement hook_watchdog to log Drupal events to custom destinations.
   Two core modules are included, dblog.module (formerly known as watchdog.module),
   and syslog.module. Other modules in contrib include an emaillog.module,
   included in the logging_alerts module. See syslog or emaillog for an
   example on how to implement hook_watchdog.
function example_watchdog($log = array()) {
  if ($log['severity'] == WATCHDOG_ALERT) {
    mysms_send($log['user']->uid,
      $log['type'],
      $log['message'],
      $log['variables'],
      $log['severity'],
      $log['referer'],
      $log['ip'],
      format_date($log['timestamp']));
  }
} */

/* TODO Implement the hook_theme registry. Combine all theme registry entries
   into one hook_theme function in each corresponding module file.
function views_theme() {
  return array(
  );
} */

/**
 * Implementation of hook_views_tables()
 */
function brilliant_gallery_views_tables() {
  $tables['brilliant_gallery'] = array(
    'name' => 'brilliant_gallery',
    /*
    'join' => array(
    'left' => array(
      'table' => 'node',
      'field' => 'nid',
    ),
    'right' => array(
      'field' => 'nid',
    ),
    ),
*/
    'fields' => array(
      'node' => array(
        'name' => t('Brilliant Gallery: A random image'),
        'handler' => array(
          'brilliant_gallery_views_handler_image_img' => t('Random image'),
          #'brilliant_gallery_views_handler_image_img_link' => t('bgImage with link'),
        ),
        /*
        'option' => array(
          '#type' => 'select',
          '#options' => 'brilliant_gallery_views_handler_filter_image_size',
        ),
*/
        'notafield' => true,
        'sortable' => false,
        'help' => t('Display one random image from the respective gallery.'),
      ),
    ),
  );
  return $tables;
}

/**
 * Views handler for displaying the image.
 */
function brilliant_gallery_views_handler_image_img($fieldinfo, $fielddata, $value, $data) {
  /*
  $node = node_load($data->nid);
  return image_display($node, $fielddata['options']);
*/

  /*
  $tmp = '.1. ' . implode( ' // ', $fieldinfo );
  $tmp .= '<br>.2. ' . implode( ' // ', $fielddata );
  $tmp .= '<br>.3. ' . implode( ' // ', $value );
  $tmp .= '<br>.4. ' . implode( ' // ', $data );
  $tmp .= '<br>.5. ' . $data->field_gallery_value;
  #field_gallery_value
*/

  $tmp    = $data->nid;
  $query  = "SELECT SQL_CACHE `field_gallery_value` FROM `content_type_page` WHERE `nid` = '". $tmp ."' ORDER BY `vid` DESC LIMIT 1";
  $string = db_result(db_query($query), 0);
  $string = str_replace(array('[bg|', ']'), '', $string);
  if (strpos($string, '|') !== false) {
    $string = substr($string, 0, (strpos($string, '|')));
  }
  if ($string == '') {
    return;
  }
  # Now get a list of images and choose one of them.
  $absolpath = realpath(file_directory_path() .'/'. variable_get('brilliant_gallery_folder', '') .'/'. $string);
  #$string .= file_directory_path() . '/' . $string;
  # Load Directory Into Array
  $poct   = -1;
  $retval = array();
  $handle = opendir($absolpath);
  while ($file = readdir($handle)) {
    $poct += 1;
    $testending = strtolower(substr($file, -4, 4));
    if (strtolower($testending) <> '.jpg' and strtolower($testending) <> 'jpeg' and strtolower($testending) <> '.gif' and strtolower($testending) <> '.png') {
      continue;
    }
    $retval[$poct] = $file;
  }
  closedir($handle);
  #print_r( $retval );
  $randimg = mt_rand(0, count($retval));
  $result  = $absolpath .'/'. $retval[$randimg];
  $temp    = getimagesize($result);
  #$imagewidth = variable_get('brilliant_gallery_maximagewidth', 150);
  # Hard-coded height for this purpose.
  $imgh = 100;
  $imgw = round(($temp[0] / $temp[1]) * $imgh);
  # Get this module's path:
  # Patching a possible problem with i18n
  $langcode = '';
  if (function_exists('i18n_get_lang')) {
    $langcode = i18n_get_lang();
  }
  $modulepath = url(drupal_get_path('module', 'brilliant_gallery'), array('absolute' => TRUE));
  # url() ads i18n codes to the URL ... we need to remove them here...
  if ($langcode <> '') {
    $modulepath = str_replace('/'. $langcode .'/', '/', $modulepath);
  }
  # Non-clean URLs need removing ?q=
  $modulepath  = str_replace("?q=", "", $modulepath);
  $result      = '<a href="'. $modulepath .'/image.php?imgp='. base64_encode($absolpath .'/'. $retval[$randimg]) .'&imgw='. $imgw * 6 .'&imgh='. $imgh * 6 .'"';
  $setname     = mt_rand(1, 9999999);
  $overbrowser = variable_get('brilliant_gallery_overbrowser', 'thickbox');
  switch ($overbrowser) {
    case 'thickbox':
      $result .= ' class="thickbox"';
      $result .= ' rel="img_'. $setname .'"';
      #$attributes['class'] = $link_class;
      #$attributes['rel'] = 'img_' . ($node->nid? $node->nid: time()); // 'insert' has no $node->nid
      break;

    case 'lightbox':
      $result .= ' rel="lightbox['. $setname .']"';
      #$attributes['rel'] = 'lightbox[' . ($node->nid? $node->nid: time()) . ']'; // 'insert' has no $node->nid
      break;

    case 'greyboxr':
      $result .= ' class="greybox"';
      #$result .= ' rel="gb_imageset[' . $setname . ']"';
      break;

    default:
      break;
  }

  if ($showcaption <> '') {
    $result .= ' title="'. $caption .'"';
  }
  $result .= '>';
  # width="' . $imgw . '"
  $result .= '<img style="display: block;border:0;align:right" src="'. $modulepath .'/image.php?imgp='. base64_encode($absolpath .'/'. $retval[$randimg]) .'&imgw='. $imgw .'&imgh='. $imgh .'" />';
  $result .= '</a>';
  return $result;
}

/**
 * Views - Generate a list of all the valid sizes that are available
 */
/*
function brilliant_gallery_views_handler_filter_image_size($op) {
  foreach (_image_get_sizes() as $key => $size) {
    $a[$key] = $size['label'];
  }
  return $a;
}
*/


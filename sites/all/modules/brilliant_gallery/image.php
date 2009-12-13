<?php
/* $Id: image.php,v 1.15.2.3.2.2.2.3 2009/07/03 16:06:18 tjfulopp Exp $ */

if (strpos(base64_decode($_GET['imgp']), "://") !== false) {
  # Fixing a possible URL injection problem. Using ':' was not enough because Windows paths contain it as well.
  header("HTTP/1.0 404 Not Found");
  exit();
}

drupalize();
function drupalize() {
  while (!@stat('./includes/bootstrap.inc')) {
    chdir('..');
  }
  #module_load_include('/includes/bootstrap.inc', 'image', 'includes/bootstrap');
  require_once './includes/bootstrap.inc';
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL); // See http://drupal.org/node/211378#comment-924059
  #drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
  #drupal_cron_run();
}
# Crucial - to suppress Devel (if installed and enabled) output appearing in the generated XML!
$GLOBALS['devel_shutdown'] = FALSE;
# Cache expiration time.
$bgcachexpire = 3600 * 24 * 3;
#$bgcachexpire = 3; # Cache expiration time.

#chdir ('../../../../');
#module_load_include('/includes/bootstrap.inc', 'image', 'includes/bootstrap');
#module_load_include('./../../../../includes/bootstrap.inc', 'image', '');
#drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

#if ( $_SERVER['SERVER_ADDR'] == '64.13.192.90' ) {
if (variable_get('brilliant_gallery_cache', 'd') == 'f') {
  #echo '.....................' . $_SERVER['SERVER_ADDR'];
  $my_data = resizeimage_wrapper_filecache();
}
else {
  $my_data = resizeimage_wrapper_dbcache();
}

#echo '....'. sess_read('vacilando');
header($my_data[0]);
#echo $my_data[0] . '<br>';
echo base64_decode($my_data[1]);
# IMPORTANT - otherwise some process after BG adds strings and breaks the image!
exit();
function resizeimage_wrapper_filecache() {
  global $bgcachexpire;
  $bgcacheid = 'bg_'. md5($_GET['imgp'] . $_GET['imgw'] . $_GET['imgh']);
  #echo $bgcacheid;
  #echo '. 0.... ';
  # Tested that both relative (eg sites/all/files/cache) and absolute (eg /home/data/tmp) tmp path settings work OK here.
  $cachetemp = variable_get('brilliant_gallery_pcache', file_directory_temp());
  #$cachedfile = file_directory_temp() .'/'. $bgcacheid;
  $cachedfile = $cachetemp .'/'. $bgcacheid;
  #$cachedfile = realpath(file_directory_temp() . '/' . $bgcacheid);
  #echo file_directory_temp()  . '/' . $bgcacheid;
  #echo " .... ";
  #echo $cachedfile;
  # See http://drupal.org/node/194923
  $lastchanged = (file_exists($cachedfile) ? filemtime($cachedfile) : false);
  if ($lastchanged === false or (time() - $lastchanged > ($bgcachexpire))) {
    #echo '. 1.... ';
    # Cache file does not exist or is too old.
    $my_data = resizeimage($_GET['imgp'], $_GET['imgw'], $_GET['imgh']);
    # Now put $my_data to cache!
    $fh = fopen($cachedfile, "w+");
    fwrite($fh, $my_data);
    fclose($fh);
    #test
    /*
       $my_data_t = unserialize( $my_data );
       $fh = fopen( $cachedfile . '_2', "w+" );
        fwrite( $fh, $my_data_t[1] );
        fclose( $fh ); 
       */

    $my_data = unserialize($my_data);
  }
  else {
    #echo '. 2.... ';
    # Cache file exists.
    $my_data = unserialize(file_get_contents($cachedfile));
  }
  return $my_data;
}

function resizeimage_wrapper_dbcache($reset = FALSE) {
  global $bgcachexpire;
  global $user;
  #$userId = $user->uid;
  $bgcacheid = 'bg_'. md5($_GET['imgp'] . $_GET['imgw'] . $_GET['imgh']);
  #echo $bgcacheid;
  static $my_data;
  #echo '0.... ';
  if (!isset($my_data) || $reset) {
    if (!$reset && ($cache = cache_get($bgcacheid)) && !empty($cache->data)) {
      #$my_data = $cache->data; echo '-1.... ' . $my_data;
      $my_data = unserialize($cache->data);
    }
    else {
      // Do your expensive calculations here, and populate $my_data
      // with the correct stuff..
      $my_data = resizeimage($_GET['imgp'], $_GET['imgw'], $_GET['imgh']);
      #echo ' -2.... ' . $bgcachexpire . ' // ' . $my_data;
      # For some reason I could not use: mysql_escape_string($my_data)
      #cache_set($bgcacheid, 'cache', time() + $bgcachexpire, $my_data);
      cache_set($bgcacheid, $my_data, 'cache', time() + $bgcachexpire);
      # FOR DRUPAL6 MUST USE:
      #cache_set($bgcacheid,  $my_data, time() + $bgcachexpire); # For some reason I could not use: mysql_escape_string($my_data)
      $my_data = unserialize($my_data);
    }
  }
  return $my_data;
}

function resizeimage($imgp, $imgw, $imgh) {
  $imagepath = base64_decode($imgp);
  #echo '.... ' . base64_decode( $imgp );
  #flush();die(' stop!');
  # Thanks to Michał Albrecht!
  $suffix = strtolower(substr($imagepath, -4));
  $imgsize = @getimagesize($imagepath);
  # http://be.php.net/getimagesize
  $head = "Content-type: {$imgsize['mime']}";
  if ($suffix == ".gif") {
    #$head = "Content-type: image/gif";
    $img = imagecreatefromgif($imagepath);
    if (!$img) {
      brokenimage("Error loading GIF");
    }
  }
  else if ($suffix == ".jpg" or $suffix == "jpeg") {
    #$head = "Content-type: image/jpeg";
    $img = imagecreatefromjpeg($imagepath);
    if (!$img) {
      brokenimage("Error loading JPG");
    }
  }
  else if ($suffix == ".png") {
    #$head = "Content-type: image/png";
    $img = imagecreatefrompng($imagepath);
    if (!$img) {
      brokenimage("Error loading PNG");
    }
  }
  # Resize the image
  $src_h   = ImageSY($img);
  $src_w   = ImageSX($img);
  $dst_img = imagecreatetruecolor($imgw, $imgh);
  imagecopyresampled($dst_img, $img, 0, 0, 0, 0, $imgw, $imgh, $src_w, $src_h);
  $img = $dst_img;
  imageinterlace($img, 1);
  imagecolortransparent($img);
  ob_start();
  if ($suffix == ".gif") {
    Imagegif($img);
  }
  else if ($suffix == ".jpg" or $suffix == ".jpeg") {
    Imagejpeg($img, '', 80);
  }
  else if ($suffix == ".png") {
    Imagepng($img);
  }
  $result = ob_get_clean();
  #ImageDestroy($img);
  $result = serialize(array($head, base64_encode($result)));
  return $result;
}

function brokenimage($msg) {
  $im  = imagecreatetruecolor(150, 30);
  $bgc = imagecolorallocate($im, 0, 0, 0);
  $tc  = imagecolorallocate($im, 255, 255, 255);
  imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
  imagestring($im, 1, 5, 5, $msg, $tc);
  imagejpeg($im);
  exit();
}

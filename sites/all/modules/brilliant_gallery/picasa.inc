<?php

/*
$purl = $_GET['purl'];
if ($purl <> '') {
  $hc = new bg_eHttpClient();
  $image = $hc->get($purl);
  #echo $image;
  $im = imagecreatefromstring($image);
  if ($im !== false) {
    header('Content-Type: image/jpg');
    imagejpeg($im);
    imagedestroy($im);
  }
}
*/
#$url_to_fetch = "http://picasaweb.google.com/data/feed/base/user/tjfulopp/albumid/5288246472868572145?alt=rss&kind=photo&hl=en_US";
if ($url_to_fetch == '') return;

// Create a special directory in temp dir for this Picasa album, then save the images there.
#$mgalleryurl = md5($url_to_fetch);
#$mkdirek = '/tmp/bg_picasa_'. $mgalleryurl;
//exec('mkdir '.$mkdirek);
mkdir($mkdirek); // See http://drupal.org/node/368583#comment-1243641

#echo '-----'.$url_to_fetch;
#$hc        = new bg_eHttpClient();
#$hc->setReferer("http://www.yahoo.com/");
#$html        = $hc->get($url_to_fetch);
$html = drupal_http_request($url_to_fetch);
$html = $html->data;
# if ($http_result->code == 200) {
#$headers    = $hc->getHeaders();
#$header        = $hc->getHeader();
#$inf        = $hc->getInfo();
// Links to full images are in this format: <enclosure type='image/jpeg' url='http://lh4.ggpht.com/_HkgjhlSfbZA/SWOgKhb6LyI/AAAAAAAABZ4/5AEu8AF01sw/DSC03173.JPG' length='0'/>
$imgchunks = explode("' url='",$html);
foreach($imgchunks as $imgchunk) {

  // Make sure the script execution does not expire in case many images need to be fetched from remote server.
  set_time_limit(180);

  $chunk2 = explode("' length='",$imgchunk);
  $imgurl = $chunk2[0];
  if (substr($imgurl,0,4) <> 'http') {
    continue;
  }
  #echo '<br>' . $imgurl . '<br>';
  // Get the file name
  $imagename = substr($imgurl,strrpos($imgurl,'/')+1);
  #echo $imagename . '<br>';
/*
  // http://lh4.ggpht.com/_HkgjhlSfbZA/SWOgKhb6LyI/AAAAAAAABZ4/5AEu8AF01sw/s288/DSC03173.JPG --->>>> ORIGINAL: http://lh4.ggpht.com/_HkgjhlSfbZA/SWOgKhb6LyI/AAAAAAAABZ4/5AEu8AF01sw/DSC03173.JPG
  $imgurlchunks = explode('/',$imgurl);
  $sizespec = $imgurlchunks[sizeof($imgurlchunks)-2];
  echo $sizespec . '<br>';
  if (strlen($sizespec) == 4 and substr($sizespec,0,1) == 's' and is_numeric(substr($sizespec,1,3))) {
    $imgurl_large = str_replace('/'.$sizespec,'',$imgurl);
    echo $imgurl_large . '<br>';
  }
*/

  #$hc = new bg_eHttpClient();
  #$image = $hc->get($imgurl);
  $image = drupal_http_request($imgurl);
  $image = $image->data;
  $foqen = $mkdirek . '/' . $imagename;
  $fp = fopen($foqen, 'w');
  #echo $foqen.'<br>';
  fwrite($fp, $image);
  fclose($fp);

  #echo '<img border="0" src="picasa.php?purl='. $imgurl .'"><br>';

}

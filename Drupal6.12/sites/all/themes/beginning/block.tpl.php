<?php
// $Id: block.tpl.php,v 1.1 2008/04/19 23:59:47 robby Exp $
// beginning drupal 6.x theme, created by robin / biboo[dot]net / nekodesign[dot]net
?>
  <div class="block block-<?php print $block->module; ?>" id="block-<?php print $block->module; ?>-<?php print $block->delta; ?>">
    <h2 class="title">
    <?php print $block->subject; ?>
    </h2>
    <div class="blockcontent"><?php print $block->content; ?></div>
 </div>

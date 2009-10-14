<?php
// $Id: block.tpl.php,v 1.2 2009/05/13 12:39:40 robby Exp $
//  beginning Web 2.0 drupal 6.x theme, created by robin / biboo.net / gazwal.com
?>
  <div class="block block-<?php print $block->module; ?>" id="block-<?php print $block->module; ?>-<?php print $block->delta; ?>">
    <h2 class="title">
    <?php print $block->subject; ?>
    </h2>
    <div class="blockcontent"><?php print $block->content; ?></div>
 </div>

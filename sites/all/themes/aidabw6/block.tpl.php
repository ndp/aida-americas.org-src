<?php
// $Id: block.tpl.php,v 1.1 $
// Created for AIDA by Buttonwillow Six
// www.buttonwillowsix.com 
// based on Beginning  by gazwal.com 

?>
  <div class="block block-<?php print $block->module; ?>" id="block-<?php print $block->module; ?>-<?php print $block->delta; ?>">
    <h2 class="title">
    <?php print $block->subject; ?>
    </h2>
    <div class="blockcontent"><?php print $block->content; ?></div>
 </div>

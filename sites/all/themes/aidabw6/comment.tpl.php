
<?php
// $Id: block.tpl.php,v 1.1 $
// Created for AIDA by Buttonwillow Six
// www.buttonwillowsix.com 
// based on Beginning  by gazwal.com
?>
  <div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status; print ' '. $zebra; ?>">
  <?php if ($picture) {print $picture;}?>
	
  <?php if ($comment->new) : ?>
    <span class="new"><?php print drupal_ucfirst($new) ?></span>
  <?php endif; ?>
  
    <h3><?php print $title ?></h3>
    
    <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
    <?php endif; ?>
    
    <?php print $content; ?>
    
    <?php if ($signature): ?>
    <div class="signature">
        <?php print $signature ?>
    </div>
    <?php endif; ?>
    
	  <?php if ($links): ?>
    <div class="commentlinks"><?php print $links ?></div>
    <?php endif; ?>
  
  </div>

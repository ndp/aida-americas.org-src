
<?php
// $Id: comment.tpl.php,v 1.2 2009/05/13 12:39:40 robby Exp $
//  beginning Web 2.0 drupal 6.x theme, created by robin / biboo.net / gazwal.com
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

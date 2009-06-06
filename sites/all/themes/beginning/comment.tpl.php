
<?php
// $Id: comment.tpl.php,v 1.1 2008/04/20 00:00:29 robby Exp $
// beginning drupal 6.x theme, created by robin / biboo[dot]net / nekodesign[dot]net
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

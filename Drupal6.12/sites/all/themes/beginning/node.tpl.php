
<?php
// $Id: node.tpl.php,v 1.1 2008/04/20 00:03:45 robby Exp $
// beginning drupal 6.x theme, created by robin / biboo[dot]net / nekodesign[dot]net
?>
<!-- start div.node -->
  <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print "-sticky"; } ?><?php if (!$status) { print " node-unpublished"; } ?>">

    <?php if ($page == 0) { ?>
    <h2 class="nodetitle"><a href="<?php print $node_url?>"><?php print $title?></a></h2>
    <?php }; ?>
    
    <div class="content">
      <?php print $content?>
    </div>
    
    <?php if ($submitted): ?>
    <div class="submitted">
    <?php print $submitted?>
    </div>
    <?php endif; ?>
    
    
    
    <div class="linkswrap">
    <?php if ($links) { ?><div class="postlinks"><?php print $links?></div><?php }; ?>
    <?php if ($terms) { ?><div class="taxonomy">Tags: <?php print $terms?></div><?php } ?>
    </div>
  
  </div>
<!-- end div.node -->

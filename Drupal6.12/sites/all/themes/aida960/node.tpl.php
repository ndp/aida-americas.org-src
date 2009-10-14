
<?php
// $Id: node.tpl.php,v 1.2 2009/05/13 12:39:40 robby Exp $
// Site by Buttonwillow Six http://www.buttonwillowsix.com
//  based on beginning Web 2.0 drupal 6.x theme, created by robin / biboo.net / gazwal.com
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

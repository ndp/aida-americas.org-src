
<?php
// $Id: node.tpl.php,v 1.1$
// Created for AIDA by Buttonwillow Six
// www.buttonwillowsix.com 
// based on Beginning  by gazwal.com
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

<!-- BW6 to Make Under Construction Graphic Appear on Page when it is Under Construction -->
<!-- Based on: http://drupal.org/node/460136-->
<?php
/**
* Prints list of the terms (as links) that are
* associated to the currently displayed node from a specific vocab
* In this case, vid 9 is the Content Status vocab
* Styles defined for uc-65 and uc-66 don't display, Style for uc-64 include under construction graphic
*/
if ( arg(0) == 'node' && is_numeric(arg(1)) ) {
    $node = node_load(arg(1));
    $vid = 9;
    $terms = taxonomy_node_get_terms_by_vocabulary($node, $vid);
    if ($terms) {
                foreach ($terms as $term) {
                echo '<div class="uc-'. $term->tid.'">';
				echo $term->name;
				echo '</div>';

          }
    }
}
?>
<!-- END BW6 to Make Under Construction Graphic Appear on Page when it is Under Construction -->

    </div>
  
  </div>
<!-- end div.node -->

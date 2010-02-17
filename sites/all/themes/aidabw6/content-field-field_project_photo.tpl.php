<?php
//BW6:This is a modified version of CCK's content-field.tpl.php file.
//It has been included in this theme so that the description field for Imagefield will appear
//below the photos (this giving a photocaption/image credit display)
//More info here: http://drupal.org/node/432846
//No FIELD LABEL INCLUDED HERE, see content-field.tpl.php if you want that back

/**
 *
 * @see template_preprocess_content_field()
 */
?>


<?php if (!$field_empty) : ?>
<div class="field field-type-<?php print $field_type_css ?> field-<?php print $field_name_css ?>">

<div class="field-items">
    <?php $count = 1;
    foreach ($items as $delta => $item) :
      if (!$item['empty']) : ?>
        <div class="field-item <?php print ($count % 2 ? 'odd' : 'even') ?>">
          <?php if ($label_display == 'inline') { ?>
            <div class="field-label-inline<?php print($delta ? '' : '-first')?>">
              <?php print t($label) ?>:&nbsp;</div>
          <?php } ?>
		
          <?php print $item['view'] ?>
		<!--BW6 This is the description field -->
		<?php if ($item['data']['description']): ?>
		 <div class="image-description">Photo: <?php print $item['data']['description']; ?></div>
		<?php endif; ?>
		<!--<P class=clearboth> </p>-->
		<!--BW6 End description field-->
        </div>


      <?php $count++;
      endif;
    endforeach;?>
  </div>


</div>
<?php endif; ?>

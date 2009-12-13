<?php
//BW6:This is a modified version of CCK's content-field.tpl.php file.
//It has been included in this theme so that the description field for Imagefield will appear
//below the photos (this giving a photocaption/image credit display)
//More info here: http://drupal.org/node/432846

/**
 * @file content-field.tpl.php
 * Default theme implementation to display the value of a field.
 *
 * Available variables:
 * - $node: The node object.
 * - $field: The field array.
 * - $items: An array of values for each item in the field array.
 * - $teaser: Whether this is displayed as a teaser.
 * - $page: Whether this is displayed as a page.
 * - $field_name: The field name.
 * - $field_type: The field type.
 * - $field_name_css: The css-compatible field name.
 * - $field_type_css: The css-compatible field type.
 * - $label: The item label.
 * - $label_display: Position of label display, inline, above, or hidden.
 * - $field_empty: Whether the field has any valid value.
 *
 * Each $item in $items contains:
 * - 'view' - the themed view for that item
 *
 * @see template_preprocess_content_field()
 */
?>
<?php if (!$field_empty) : ?>
<div class="field field-type-<?php print $field_type_css ?> field-<?php print $field_name_css ?>">
  <?php if ($label_display == 'above') : ?>
    <div class="field-label"><?php print t($label) ?>:&nbsp;</div>
  <?php endif;?>
  <div class="field-items">
    <?php $count = 1;
    foreach ($items as $delta => $item) :
      if (!$item['empty']) : ?>
        <div class="field-item <?php print ($count % 2 ? 'odd' : 'even') ?>">
          <?php if ($label_display == 'inline') { ?>
            <div class="field-label-inline<?php print($delta ? '' : '-first')?>">
              <?php print t($label) ?>:&nbsp;</div>
          <?php } ?>
			<!--BW6 This is the description field -->
			<?php if ($item['data']['description']): ?>
			 <div class="image-description">Photo: <?php print $item['data']['description']; ?></div>
			<?php endif; ?>
			<P class=clearboth> </p>
			<!--BW6 End description field-->
          <?php print $item['view'] ?>
		

        </div>

			
		


      <?php $count++;
      endif;
    endforeach;?>
  </div>
</div>
<?php endif; ?>

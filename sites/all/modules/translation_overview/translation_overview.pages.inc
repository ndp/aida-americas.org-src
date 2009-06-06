<?php
// $Id: translation_overview.pages.inc,v 1.9 2009/04/16 19:47:28 drewish Exp $

function translation_overview_node_page($node) {
  drupal_set_title(t('Translations of %title', array('%title' => $node->title)));

  return drupal_get_form('translation_overview_node_form', $node);
}

/**
 * Overview page for a node's translations.
 *
 * Based on the translation.module's translation_node_overview().
 *
 * @param $node
 *   Node object.
 */
function translation_overview_node_form(&$form_state, $node) {
  if ($node->tnid) {
    // Already part of a set, grab that set.
    $tnid = $node->tnid;
    $translations = translation_node_get_translations($node->tnid);
  }
  else {
    // We have no translation source nid, this could be a new set, emulate that.
    $tnid = $node->nid;
    $translations = array($node->language => $node);
  }
  $priorities = translation_overview_get_node_priority($node);

  $form['languages']['#tree'] = TRUE;

  foreach (language_list() as $lang_code => $language) {
    // Skip over any disabled languages.
    if (!$language->enabled) {
      continue;
    }

    $form['languages'][$lang_code]['priority'] = array(
      '#type' => 'radios',
      '#default_value' => $priorities[$lang_code],
      '#options' => array(
        TRANSLATION_OVERVIEW_HIGH => t('High'),
        TRANSLATION_OVERVIEW_NORMAL => t('Normal'),
        TRANSLATION_OVERVIEW_IGNORE => t('Ignore'),
      ),
      // If there's no manager role then everyone gets a chance. Otherwise
      // check that they're a manager for this language.
      '#access' => translation_overview_is_manager($lang_code),
    );

    if (isset($translations[$lang_code])) {
      // Existing translation in the translation set: display status.
      // We load the full node to check whether the user can edit it.
      $translation_node = node_load($translations[$lang_code]->nid);
      if ($translation_node->nid == $tnid) {
        // Original shouldn't have a priority.
        $form['languages'][$lang_code]['priority']['#access'] = FALSE;
        $form['languages'][$lang_code]['language'] = array(
          '#value' =>  '<strong>'. $language->name .'</strong> (source)',
        );
      }
      else {
        $form['languages'][$lang_code]['language'] = array(
          '#value' => $language->name,
        );
      }
      $form['languages'][$lang_code]['title'] = array(
        '#value' => l($translation_node->title, 'node/'. $translation_node->nid),
      );
      $form['languages'][$lang_code]['status'] = array(
        '#value' => translation_overview_translation_link($node, $translation_node, $lang_code, TRUE),
      );
    }
    else {
      // No such translation in the set yet: help user to create it.
      $form['languages'][$lang_code]['language'] = array(
        '#value' => $language->name,
      );
      $form['languages'][$lang_code]['title'] = array(
        '#value' => t('n/a'),
      );
      $form['languages'][$lang_code]['status'] = array(
        '#value' => translation_overview_translation_link($node, NULL, $lang_code, TRUE),
      );
    }
  }

  $form['tnid'] = array(
    '#type' => 'value',
    '#value' => $tnid,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  return $form;
}

function translation_overview_node_form_submit($form, &$form_state) {
  $row = array('tnid' => (int) $form_state['values']['tnid']);
  foreach ($form_state['values']['languages'] as $language => $values) {
    $row[translation_overview_field_name($language)] = $values['priority'];
  }

  // We can assume that there will alwyas be a record with the given tnid
  // because translation_overview_get_node_priority() has been called and it
  // creates records if one is not present.
  if (drupal_write_record('translation_overview_priority', $row, 'tnid') !== FALSE) {
    drupal_set_message(t('The translation priorities have been saved.'));
  }
  else {
    drupal_set_message(t('There was a problem saving the translation priorities.'), 'error');
  }
}


function theme_translation_overview_node_form($form) {
  drupal_add_css(drupal_get_path('module', 'translation_overview') .'/translation_overview.css');

  $is_manager = translation_overview_is_manager();

  $header = array(
    array('data' => t('Language')),
    array('data' => t('Title')),
    array('data' => t('Status')),
  );

  if ($is_manager) {
    $header[] = array('data' => t('Priority'), 'colspan' => 3);
  }

  $rows = array();
  foreach (element_children($form['languages']) as $key) {
    $row = array();
    $row[] = array('data' => drupal_render($form['languages'][$key]['language']));
    $row[] = array('data' => drupal_render($form['languages'][$key]['title']));
    $row[] = array('data' => drupal_render($form['languages'][$key]['status']), 'class' => 'status');
    if ($is_manager) {
      foreach (element_children($form['languages'][$key]['priority']) as $priority) {
        $row[] = array('data' => drupal_render($form['languages'][$key]['priority'][$priority]));
      }
    }
    $rows[] = $row;
  }
  return theme('table', $header, $rows, array('class' => 'trov')) . drupal_render($form);
}


/**
 * List node administration filters that can be applied.
 *
 * This is a fork of node_filters().
 */
function translation_overview_node_filters() {
  // Regular filters
  $filters['status'] = array(
    'title' => t('Status'),
    'options' => array(
      'status-1' => t('Published'),
      'status-0' => t('Not published'),
      'promote-1' => t('Promoted'),
      'promote-0' => t('Not promoted'),
      'sticky-1' => t('Sticky'),
      'sticky-0' => t('Not sticky'),
      'translate-0' => t('Up-to-date translation'),
      'translate-1' => t('Outdated translation'),
    ),
  );

  $filters['type'] = array(
    'title' => t('type'),
    'options' => translation_overview_node_types(),
  );

  // The taxonomy filter
  if ($taxonomy = module_invoke('taxonomy', 'form_all', 1)) {
    $filters['category'] = array('title' => t('category'), 'options' => $taxonomy);
  }

  return $filters;
}

/**
 * Return form for node administration filters.
 *
 * This is a fork of node_filter_form().
 */
function translation_overview_filter_form() {
  // We reuse a bunch of the node.module's stuff.
  module_load_include('inc', 'node', 'node.admin');

  $session = &$_SESSION['translation_overview_filter'];
  $session = is_array($session) ? $session : array();
  $filters = translation_overview_node_filters();

  $i = 0;
  $form['filters'] = array(
    '#type' => 'fieldset',
    '#title' => t('Show only items where'),
    '#theme' => 'node_filters',
  );
  $form['#submit'][] = 'translation_overview_filter_form_submit';
  foreach ($session as $filter) {
    list($type, $value) = $filter;
    if ($type == 'category') {
      // Load term name from DB rather than search and parse options array.
      $value = module_invoke('taxonomy', 'get_term', $value);
      $value = $value->name;
    }
    else {
      $value = $filters[$type]['options'][$value];
    }
    if ($i++) {
      $form['filters']['current'][] = array('#value' => t('<em>and</em> where <strong>%a</strong> is <strong>%b</strong>', array('%a' => $filters[$type]['title'], '%b' => $value)));
    }
    else {
      $form['filters']['current'][] = array('#value' => t('<strong>%a</strong> is <strong>%b</strong>', array('%a' => $filters[$type]['title'], '%b' => $value)));
    }
    if (in_array($type, array('type', 'language'))) {
      // Remove the option if it is already being filtered on.
      unset($filters[$type]);
    }
  }

  foreach ($filters as $key => $filter) {
    $names[$key] = $filter['title'];
    $form['filters']['status'][$key] = array('#type' => 'select', '#options' => $filter['options']);
  }

  $form['filters']['filter'] = array('#type' => 'radios', '#options' => $names, '#default_value' => 'status');
  $form['filters']['buttons']['submit'] = array('#type' => 'submit', '#value' => (count($session) ? t('Refine') : t('Filter')));
  if (count($session)) {
    $form['filters']['buttons']['undo'] = array('#type' => 'submit', '#value' => t('Undo'));
    $form['filters']['buttons']['reset'] = array('#type' => 'submit', '#value' => t('Reset'));
  }

  drupal_add_js('misc/form.js', 'core');

  return $form;
}

/**
 * Process result from node administration filter form.
 *
 * This is a fork of node_filter_form().
 */
function translation_overview_filter_form_submit($form, &$form_state) {
  $filters = translation_overview_node_filters();
  switch ($form_state['values']['op']) {
    case t('Filter'):
    case t('Refine'):
      if (isset($form_state['values']['filter'])) {
        $filter = $form_state['values']['filter'];

        // Flatten the options array to accommodate hierarchical/nested options.
        $flat_options = form_options_flatten($filters[$filter]['options']);

        if (isset($flat_options[$form_state['values'][$filter]])) {
          $_SESSION['translation_overview_filter'][] = array($filter, $form_state['values'][$filter]);
        }
      }
      break;
    case t('Undo'):
      array_pop($_SESSION['translation_overview_filter']);
      break;
    case t('Reset'):
      $_SESSION['translation_overview_filter'] = array();
      break;
  }
}

/**
 * Build the where clause for a filtered query.
 *
 * This is a fork of node_filter_form_submit().
 */
function translation_overview_build_filter_query() {
  $filters = translation_overview_node_filters();

  // Build query
  $join = $where = $where_args = array();
  if (isset($_SESSION['translation_overview_filter'])) {
    foreach ((array) $_SESSION['translation_overview_filter'] as $index => $filter) {
      list($key, $value) = $filter;
      switch ($key) {
        case 'status':
          // Note: no exploitable hole as $key/$value have already been checked when submitted
          list($key, $value) = explode('-', $value, 2);
          $where['status'] = 'n.'. $key .' = %d';
          break;
        case 'category':
          $table = "tn$index";
          $where[$table] = "$table.tid = %d";
          $join[$table] = "INNER JOIN {term_node} $table ON n.nid = $table.nid ";
          break;
        case 'type':
          $where['type'] = "n.type = '%s'";
          break;
      }
      $where_args[] = $value;
    }
  }

  // Make sure we limit it to translation enabled types.
  if (empty($where['type'])) {
    $types = array_keys(translation_overview_node_types());
    $where[] = 'n.type IN ('. db_placeholders($types, 'varchar') .')';
    $where_args = array_merge($where_args, $types);
  }

  return array('join' => $join, 'where' => $where, 'where_args' => $where_args);
}

/**
 * Translation overview page.
 */
function translation_overview_manager_page() {
  drupal_add_css(drupal_get_path('module', 'translation_overview') .'/translation_overview.css');

  $rows_per_page = 30;

  // Get a list of the enabled languages that this user manages.
  $languages = array();
  foreach (language_list() as $key => $language) {
    if ($language->enabled && translation_overview_is_manager($language->language)) {
      $languages[$key] = $language->name;
    }
  }

  // Bail if there are no translatable nodes
  if (count(translation_overview_node_types()) == 0) {
    drupal_set_message(t('There are no translatable node types on this site.'), 'error');
    return '';
  }

  $header = array(
    array('field' => 'n.title', 'data' => t('Title'), 'sort' => 'asc'),
    array('field' => 'n.type', 'data' => t('Type')),
    array('field' => 'n.created', 'data' => t('Created')),
  );
  foreach ($languages as $lang_code => $lang_name) {
    $header[] = array('data' => str_replace('-', '-<br />', $lang_code), 'class' => 'trov-lang', 'title' => $lang_name);
  }

  $query = translation_overview_build_filter_query();
  $sql = "SELECT n.nid, n.title, n.type, n.created FROM {node} n ". implode(' ', $query['join']) ."
    WHERE (n.nid = n.tnid OR n.tnid = 0) AND n.language <> '' AND n.language IS NOT NULL
    AND ". implode(' AND ', $query['where']) . tablesort_sql($header);

  $rows = array();
  $result = pager_query(db_rewrite_sql($sql), $rows_per_page, 0, NULL, $query['where_args']);
  while ($node = db_fetch_object($result)) {
    $node = node_load($node->nid);

    $row = array(
      array('data' => l(translation_overview_trimmed_title($node), 'node/'. $node->nid .'/translate', array('attributes' => array('title' => $node->title), 'query' => array('destination' => $_GET['q'])))),
      array('data' => check_plain($node->type)),
      array('data' => format_date($node->created, 'custom', 'j M Y')),
    );

    // Load the node's translations and then fill in the table with the status.
    $translations = (array) translation_node_get_translations($node->tnid);
    foreach ($languages as $lang_code => $lang_name) {
      $translation = empty($translations[$lang_code]->nid) ? NULL : node_load($translations[$lang_code]->nid);
      $row[$lang_code] = array(
        'data' => translation_overview_translation_link($node, $translation, $lang_code, FALSE),
        'class' => 'status',
      );
    }

    $rows[] = $row;
  }

  return drupal_get_form('translation_overview_filter_form')
    . theme('table', $header, $rows, array('class' => 'trov-all'))
    . theme('pager', NULL, $rows_per_page);
}

/**
 * Show a listing of the languages that translators can look at.
 */
function translation_overview_assignment_lang_page() {
  $items = array();
  foreach (language_list() as $language) {
    if ($language->enabled) {
      $items[] = l($language->name, 'admin/content/translation_overview_assignments/'. $language->language);
    }
  }
  return theme('item_list', $items);
}

function translation_overview_assignment_page($language) {
  drupal_add_css(drupal_get_path('module', 'translation_overview') .'/translation_overview.css');

  $rows_per_page = 30;
  $node_types = node_get_types('names');
  $languages = language_list();

  // Bail if there are no translatable nodes
  if (count(translation_overview_node_types()) == 0) {
     drupal_set_message(t('There are no translatable node types on this site.'), 'error');
     return '';
  }

  $header = array(
    array('field' => 'n.title', 'data' => t('Title')),
    array('field' => 'translation_status', 'data' => t('Status'), 'sort' => 'desc'),
    array('field' => 'n.type', 'data' => t('Type')),
    array('field' => 'n.language', 'data' => t('Source language')),
    array('field' => 'n.created', 'data' => t('Created')),
  );

  // We want to sort the nodes by the status so we have to resort to this SQL
  // CASE statement.
  $rows = array();
  $query = translation_overview_build_filter_query();
  $language_field = translation_overview_field_name($language);
  $sql = "SELECT n.nid, n.title, n.type, n.status, n.language, n.created, nt.nid AS translation_nid, CASE
    WHEN n.language = '%s' " /* ORIGINAL */ ." THEN 0
    WHEN nt.tnid IS NULL   " /* MISSING  */ ." THEN 3 + 2 * top.$language_field
    WHEN nt.translate = 0  " /* COMPLETE */ ." THEN 1
    WHEN n.nid = n.tnid    " /* OUTDATED */ ." THEN 2 + 2 * top.$language_field
    ELSE -1 END AS translation_status
    FROM {node} n
    LEFT JOIN {node} nt ON n.nid = nt.tnid AND nt.language = '%s'
    INNER JOIN {translation_overview_priority} top ON n.nid = top.tnid AND top.$language_field <> %d
    ". implode(' ', $query['join']) ."
    WHERE (n.nid = n.tnid OR n.tnid = 0) AND n.language NOT IN ('', '%s') AND n.language IS NOT NULL
    AND ". implode(' AND ', $query['where'])
    . tablesort_sql($header);
  // The %s in the CASE causes pager_query() to screw up the count query so build it by hand and eat the extra %s.
  $count_sql = "SELECT COUNT(n.nid), '%s' AS junk
    FROM {node} n
    LEFT JOIN {node} nt ON n.nid = nt.tnid AND nt.language = '%s'
    INNER JOIN {translation_overview_priority} top ON n.nid = top.tnid AND top.$language_field <> %d
    ". implode(' ', $query['join']) ."
    WHERE (n.nid = n.tnid OR n.tnid = 0) AND n.language NOT IN ('', '%s') AND n.language IS NOT NULL
    AND ". implode(' AND ', $query['where']);
  // We need to put the priority and language that we use as an argument early
  // in the query at the beginning of the arguments list.
  $args = array_merge(array($language, $language, TRANSLATION_OVERVIEW_IGNORE, $language), $query['where_args']);
  $rows = array();
  $result = pager_query(db_rewrite_sql($sql), $rows_per_page, 0, db_rewrite_sql($count_sql), $args);
  while ($n = db_fetch_object($result)) {
    $node = node_load($n->nid);
    $translation = empty($n->translation_nid) ? NULL : node_load($n->translation_nid);
    $rows[] = array(
      array('data' => node_access('view', $node) ? l(translation_overview_trimmed_title($node, 30), 'node/'. $node->nid, array('attributes' => array('title' => $node->title))) : translation_overview_trimmed_title($node, 30)),
      array('data' => translation_overview_translation_link($node, $translation, $language, TRUE), 'class' => 'status'),
      array('data' => isset($node_types[$node->type]) ? $node_types[$node->type] : check_plain($node->type)),
      array('data' => isset($languages[$node->language]) ? $languages[$node->language]->name : check_plain($node->language)),
      array('data' => format_date($node->created, 'custom', 'j M Y')),
    );
  }

  return drupal_get_form('translation_overview_filter_form')
    . theme('table', $header, $rows, array('class' => 'trov'))
    . theme('pager', NULL, $rows_per_page);
}

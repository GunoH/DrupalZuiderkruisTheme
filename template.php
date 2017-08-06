<?php

function zuiderkruis_theme(&$existing, $type, $theme, $path) {
  $hooks['user_login_block'] = array(
      'template' => 'templates/user-login-block',
      'render element' => 'form',
  );
  return $hooks;
}
function zuiderkruis_preprocess_user_login_block(&$vars) {
  $vars['name'] = render($vars['form']['name']);
  $vars['pass'] = render($vars['form']['pass']);
  $vars['submit'] = render($vars['form']['actions']['submit']);
  $vars['rendered'] = drupal_render_children($vars['form']);
}

function zuiderkruis_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    $form['search_block_form']['#title'] = t('Search'); // Change the text on the label element
    $form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibilty
    $form['search_block_form']['#size'] = 19;  // define size of the textfield
    //$form['search_block_form']['#default_value'] = t('Search'); // Set a default value for the textfield
    $form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
    $form['actions']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/search-button.png');
    // Add extra attributes to the text box
    //$form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = '" . t('Search') . "';}";
    //$form['search_block_form']['#attributes']['onfocus'] = "if (this.value == " . t('Search') . ") {this.value = '';}";
    // Prevent user from searching the default text
    //$form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='" . t('Search') ."'){ alert('Please enter a search'); return false; }";
    // Alternative (HTML5) placeholder attribute instead of using the javascript
    $form['search_block_form']['#attributes']['placeholder'] = t('Search...');
  }
}
function zuiderkruis_preprocess_node(&$variables) {
  // Customize 'Read more' link.
  $txt = isset($variables['content']['body']['#object']->field_read_more_link_text['und']) ?
         $variables['content']['body']['#object']->field_read_more_link_text['und'][0]['value'] : null;
  if (isset($txt) && $variables['view_mode'] == 'teaser') {
    $variables['content']['links']['node']['#links']['node-readmore']['title'] = $txt;
  }
}
function zuiderkruis_preprocess_html(&$vars) {
  $viewport = array(
   '#tag' => 'meta',
   '#attributes' => array(
     'name' => 'viewport',
     'content' => 'width=device-width, initial-scale=1',
   ),
  );
  drupal_add_html_head($viewport, 'viewport');

    // Add collapsible library to agenda-volgen page
    if ($variables['node']['nid'] = 52){
        drupal_add_js('misc/form.js');
        drupal_add_js('misc/collapse.js');
    }
}

?>

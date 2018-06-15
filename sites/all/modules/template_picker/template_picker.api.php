<?php

/**
 * Alter the list of form ids to be blacklisted from the Template Picker.
 *
 * @param $form_id_blacklist
 *
 * @return void
 */
function hook_template_picker_blacklist_alter(&$form_id_blacklist) {
  $form_id_blacklist[] = 'form_id';
}

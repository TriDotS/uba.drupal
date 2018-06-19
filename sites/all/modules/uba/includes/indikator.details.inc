<?php

/**
 * Implements Menu-Item callback "$items['indikator/%']" .
 */

function ressourcendaten_page_indikator($indicator_id) {
	$module_path = drupal_get_path('module', 'uba');
	$data = array( 'uba' => array( 'path' => $module_path, ), );
	drupal_add_js($module_path. '/my-app/dist/app.js');
	$variables['indicator_id'] = $indicator_id;
	$indicator = file_get_contents('http://uba-ckan.ext.datengrab.cc/api/indicators/'.$variables['indicator_id']);
	$variables['indicator'] = json_decode($indicator);
	//dvm($variables);
	return theme('ressourcendaten_page_indikator',$variables);
}

/**
 * Implements hook_preprocess_THEME().
 *
 * This Example preprocesses some variables to pass to the theme function/file.
 * DELETE IF UNNECESSARY! You probably won't need it, but it is here as an example
 */
function uba_preprocess_ressourcendaten_page_indikator(&$variables) {
	$series = $variables['indicator']->series;
	
	$variables['preprocessed_indicator']                        = new stdClass();
	$variables['preprocessed_indicator']->title                 = new stdClass();
	$variables['preprocessed_indicator']->title->name           = $variables['indicator']->title;
	$variables['preprocessed_indicator']->categories            = $variables['indicator']->categories;
	$variables['preprocessed_indicator']->yAxis                 = new stdClass();;
	$variables['preprocessed_indicator']->yAxis->title          = new stdClass();;
	$variables['preprocessed_indicator']->yAxis->title->text    = 'Export (Tonnen)';
	$variables['preprocessed_indicator']->series                = modifySeries($variables['indicator']->series);
}
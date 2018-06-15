<?php

	/**
	 * Add body classes if certain regions have content.
	 */
	function uba_preprocess_html(&$variables)
	{
		// Add variables for path to theme.
		$variables['base_path'] = base_path();
		$variables['path_to_resbartik'] = drupal_get_path('theme', 'uba');
		
		// Add local.css stylesheet
//		if (file_exists(drupal_get_path('theme', 'uba') . '/css/local.css')) {
//			drupal_add_css(drupal_get_path('theme', 'uba') . '/css/local.css',
//				array('group' => CSS_THEME, 'every_page' => TRUE));
//		}
	
	}
	
	/**
	 * Override or insert variables into the page template.
	 */
	function uba_process_page(&$variables)
	{
		// Hook into color.module.
		if (module_exists('color')) {
			_color_page_alter($variables);
		}
		// Always print the site name and slogan, but if they are toggled off, we'll
		// just hide them visually.
		$variables['hide_site_name'] = theme_get_setting('toggle_name') ? FALSE : TRUE;
		$variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
		if ($variables['hide_site_name']) {
			// If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
			$variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
		}
		if ($variables['hide_site_slogan']) {
			// If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
			$variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
		}
		// Since the title and the shortcut link are both block level elements,
		// positioning them next to each other is much simpler with a wrapper div.
		if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
			// Add a wrapper div using the title_prefix and title_suffix render elements.
			$variables['title_prefix']['shortcut_wrapper'] = array(
				'#markup' => '<div class="shortcut-wrapper clearfix">',
				'#weight' => 100,
			);
			$variables['title_suffix']['shortcut_wrapper'] = array(
				'#markup' => '</div>',
				'#weight' => -99,
			);
			// Make sure the shortcut link is the first item in title_suffix.
			$variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
		}
	}
	
	function _phptemplate_variables($hook, $vars = array()) {
		$path = base_path() . path_to_theme() .'/';
		$vars['theme_path'] = $path;

		return $vars;
	}
	
	function uba_preprocess_page(&$variables) {
		$variables['uba_menu']         = uba_uba_menu();
	}
	
	/**
	 * Returns an array of links to be rendered as the Main menu.
	 */
	function uba_uba_menu() {
		$menu = menu_navigation_links('ressourcendaten');
		return $vars['uba_menu'] = theme('links__ressourcendaten', array('links' => $menu));
	}
	
	function uba_links__ressourcendaten(&$variables) {
		$links = '<ul class="list-reset flex">';
		foreach ($variables['links'] as $link) {
			$link['attributes']['style'] = "background-image: url('".base_path().path_to_theme()."/images/bg-box-pattern.png')";
			$link['attributes']['class'][] = "text-center block mx-1 p-2 no-underline text-black";
			$link['html'] = true;
			
			$linkcontent = "<div class='bg-white p-4'><h2>".$link['title']."</h2><p>".$link['attributes']['title']."</p></div>";
			
			$links .= "<li class='flex-1'>".l($linkcontent, $link['href'], $link)."</li>";
		}
		$links .= '</ul>';
		return $links;
	}
	
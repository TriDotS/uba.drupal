<?php
	
	/**
	 * @file
	 * Bartik's theme implementation to display a single Drupal page.
	 *
	 * The doctype, html, head and body tags are not in this template. Instead they
	 * can be found in the html.tpl.php template normally located in the
	 * modules/system directory.
	 *
	 * Available variables:
	 *
	 * General utility variables:
	 * - $base_path: The base URL path of the Drupal installation. At the very
	 *   least, this will always default to /.
	 * - $directory: The directory the template is located in, e.g. modules/system
	 *   or themes/bartik.
	 * - $is_front: TRUE if the current page is the front page.
	 * - $logged_in: TRUE if the user is registered and signed in.
	 * - $is_admin: TRUE if the user has permission to access administration pages.
	 *
	 * Site identity:
	 * - $front_page: The URL of the front page. Use this instead of $base_path,
	 *   when linking to the front page. This includes the language domain or
	 *   prefix.
	 * - $logo: The path to the logo image, as defined in theme configuration.
	 * - $site_name: The name of the site, empty when display has been disabled
	 *   in theme settings.
	 * - $site_slogan: The slogan of the site, empty when display has been disabled
	 *   in theme settings.
	 * - $hide_site_name: TRUE if the site name has been toggled off on the theme
	 *   settings page. If hidden, the "element-invisible" class is added to make
	 *   the site name visually hidden, but still accessible.
	 * - $hide_site_slogan: TRUE if the site slogan has been toggled off on the
	 *   theme settings page. If hidden, the "element-invisible" class is added to
	 *   make the site slogan visually hidden, but still accessible.
	 *
	 * Navigation:
	 * - $main_menu (array): An array containing the Main menu links for the
	 *   site, if they have been configured.
	 * - $secondary_menu (array): An array containing the Secondary menu links for
	 *   the site, if they have been configured.
	 * - $breadcrumb: The breadcrumb trail for the current page.
	 *
	 * Page content (in order of occurrence in the default page.tpl.php):
	 * - $title_prefix (array): An array containing additional output populated by
	 *   modules, intended to be displayed in front of the main title tag that
	 *   appears in the template.
	 * - $title: The page title, for use in the actual HTML content.
	 * - $title_suffix (array): An array containing additional output populated by
	 *   modules, intended to be displayed after the main title tag that appears in
	 *   the template.
	 * - $messages: HTML for status and error messages. Should be displayed
	 *   prominently.
	 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
	 *   (e.g., the view and edit tabs when displaying a node).
	 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
	 *   menu administration interface.
	 * - $feed_icons: A string of all feed icons for the current page.
	 * - $node: The node object, if there is an automatically-loaded node
	 *   associated with the page, and the node ID is the second argument
	 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
	 *   comment/reply/12345).
	 *
	 * Regions:
	 * - $page['header']: Items for the header region.
	 * - $page['featured']: Items for the featured region.
	 * - $page['highlighted']: Items for the highlighted content region.
	 * - $page['help']: Dynamic help text, mostly for admin pages.
	 * - $page['content']: The main content of the current page.
	 * - $page['sidebar_first']: Items for the first sidebar.
	 * - $page['triptych_first']: Items for the first triptych.
	 * - $page['triptych_middle']: Items for the middle triptych.
	 * - $page['triptych_last']: Items for the last triptych.
	 * - $page['footer_firstcolumn']: Items for the first footer column.
	 * - $page['footer_secondcolumn']: Items for the second footer column.
	 * - $page['footer_thirdcolumn']: Items for the third footer column.
	 * - $page['footer_fourthcolumn']: Items for the fourth footer column.
	 * - $page['footer']: Items for the footer region.
	 *
	 * @see template_preprocess()
	 * @see template_preprocess_page()
	 * @see template_process()
	 * @see bartik_process_page()
	 * @see html.tpl.php
	 */
?>
<div id="page-wrapper">
	<div id="page">
		<!-- BEGIN: Header -->
		<div class="border-green border-b-8 mb-4" style="background: url('<?php print '/'. $directory .'/' ?>/images/bg-header-pattern.png');">
			<div class="container mx-auto">
				<nav class="flex items-center justify-between flex-wrap px-6 pb-6">
					<div class="flex items-center flex-no-shrink text-white mr-6">
						<?php if ( $logo ): ?>
							<a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo" class="w-48">
								<img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>"/>
							</a>
						<?php endif; ?>
					</div>
					<div class="block lg:hidden">
						<!--<button class="flex items-center px-3 py-2 border rounded text-teal-lighter border-teal-light hover:text-white hover:border-white">
							<svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
						</button>-->
					</div>
					<div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
						<div class="text-sm lg:flex-grow">
						</div>
						<div>
							<!--<a href="#" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal hover:bg-white mt-4 lg:mt-0">ToDo: Suchfeld</a>-->
						</div>
					</div>
				</nav>
			</div>
		</div>
		<!-- END: Header -->
		<!-- BEGIN: Main Menu -->
		<div id="uba-menu" class="container mx-auto">
			<?php if ( $uba_menu ): ?>
				<?php print render($uba_menu); ?>
			<?php endif; ?>
		</div>
		
		<?php if ( $messages ): ?>
			<div id="messages">
				<div class="section clearfix">
					<?php print $messages; ?>
				</div>
			</div> <!-- /.section, /#messages -->
		<?php endif; ?>
		
		<div id="main-wrapper" class="clearfix">
			<div id="main" class="container mx-auto mt-8">
				<div id="content" class="column">
					<div class="section">
						<a id="main-content"></a>
						<?php print render($title_prefix); ?>
						<?php if (!drupal_is_front_page()) { ?>
							<?php if ( $title ): ?>
								<h1 class="title" id="page-title">
									<?php print $title; ?>
								</h1>
							<?php endif; ?>
						<?php } ?>
						<?php print render($title_suffix); ?>
						<?php if ( $tabs ): ?>
							<div class="tabs">
								<?php print render($tabs); ?>
							</div>
						<?php endif; ?>
						<?php print render($page[ 'help' ]); ?>
						<?php if ( $action_links ): ?>
							<ul class="action-links">
								<?php print render($action_links); ?>
							</ul>
						<?php endif; ?>
						<?php if (!drupal_is_front_page()) { ?>
							<?php print render($page['content']); ?>
						<?php } ?>
						<?php print $feed_icons; ?>
					
					</div>
				</div> <!-- /.section, /#content -->
			</div>
		</div> <!-- /#main, /#main-wrapper -->
		
		<div id="footer-wrapper">
			<div class="section">
				<?php if ( $page[ 'footer' ] ): ?>
					<div id="footer" class="clearfix">
						<?php print render($page[ 'footer' ]); ?>
					</div> <!-- /#footer -->
				<?php endif; ?>
			
			</div>
		</div> <!-- /.section, /#footer-wrapper -->
	
	</div>
</div> <!-- /#page, /#page-wrapper -->

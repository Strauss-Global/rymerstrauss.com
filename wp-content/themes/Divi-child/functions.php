<?php
/* -- Enqueue necessary CSS and JS files for Child Theme -- */

function fs_theme_enqueue_stuff() {
	// Divi assigns its style.css with this handle
	$parent_handle = 'divi-style'; 
	// Get the current child theme data
	$current_theme = wp_get_theme(); 
	// get the parent version number of the current child theme
	$parent_version = $current_theme->parent()->get('Version'); 
	// get the version number of the current child theme
	$child_version = $current_theme->get('Version'); 
	// we check file date of child stylesheet and script, so we can append to version number string (for cache busting)
	$style_cache_buster = date("YmdHis", filemtime( get_stylesheet_directory() . '/style.css'));
	$script_cache_buster = date("YmdHis", filemtime( get_stylesheet_directory() . '/script.js'));
	// first we pull in the parent theme styles that it needs
	wp_enqueue_style( $parent_handle, get_template_directory_uri() . '/style.css', array(), $parent_version );
	// then we get the child theme style.css file, which is dependent on the parent theme style, then append string of child version and file date
	wp_enqueue_style( 'fs-child-style', get_stylesheet_uri(), array( $parent_handle ), $child_version .'-'. $style_cache_buster );
	// will grab the script file from the child theme directory, and is reliant on jquery and the divi-custom-script (so it comes after that one)
	wp_enqueue_script( 'fs-child-script', get_stylesheet_directory_uri() . '/script.js', array('jquery', 'divi-custom-script'), $child_version .'-'. $script_cache_buster, true);
}
add_action( 'wp_enqueue_scripts', 'fs_theme_enqueue_stuff' );

/* -- Enqueue WP Admin files for Child Theme -- */

function fs_theme_enqueue_admin_stuff() {
	// Get the current child theme data
	$current_theme = wp_get_theme(); 
	// get the version number of the current child theme
	$child_version = $current_theme->get('Version'); 
	// we check file date of WP backend stylesheet, so we can append to version number string (for cache busting)
	$style_cache_buster = date("YmdHis", filemtime( get_stylesheet_directory() . '/wp-admin.css'));
	// Begin enqueue for CSS file that loads in WP backend
	wp_enqueue_style( 'fs-child-style-admin', get_stylesheet_directory_uri() . '/wp-admin.css', array(), $child_version .'-'. $style_cache_buster );
}
add_action( 'admin_enqueue_scripts', 'fs_theme_enqueue_admin_stuff' );

/* -- Helpers -- */

// Creates shortcode to allow automatically pulling in the Site Title (from General Settings)
// This is used by default in the copyright text within the Code module of the Global Footer in the Divi Theme Builder
function fs_site_title_shortcode() {
	return get_bloginfo( 'name' );
}
add_shortcode( 'fs_site_title','fs_site_title_shortcode' );

/* -- WP Dashboard Widgets -- */

// Remove certain widgets from the backend WP Dashboard page
function fs_remove_dashboard_widget() {
	// Pressable widget
	remove_meta_box( 'pressable_dashboard_widget', 'dashboard', 'normal' );
} 
add_action( 'wp_dashboard_setup', 'fs_remove_dashboard_widget' );

/* -- Divi -- */

// Creates shortcode to allow placing Divi Library module inside of another module's text area. Creates a shortcode to show the Library module.
// https://www.creaweb2b.com/en/how-to-add-a-divi-section-or-module-inside-another-module/
// example usage: [showmodule id="123"]
function showmodule_shortcode($moduleid) {
	extract(shortcode_atts(array('id' =>'*'),$moduleid));   
	return do_shortcode('[et_pb_section global_module="'.$id.'"][/et_pb_section]');
}
add_shortcode('showmodule', 'showmodule_shortcode');

// Adds new admin column to show the shortcode ID in the Divi Library page table
function fs_create_shortcode_column( $columns ) {
	$columns['fs_shortcode_id'] = 'Library Item Shortcode';
	return $columns;
}
// Display shortcode column info on Divi Library page table
function fs_shortcode_column_content( $column, $id ) {
	if( 'fs_shortcode_id' == $column ) {
		echo '<p>[showmodule id="' . $id . '"]</p>';
	}
}
// create new shortcode column in et_pb_layout screen
add_filter( 'manage_et_pb_layout_posts_columns', 'fs_create_shortcode_column', 5 );
// add the shortcode content to the new column
add_action( 'manage_et_pb_layout_posts_custom_column', 'fs_shortcode_column_content', 5, 2 );

/* -- Gravity Forms -- */

// This filter can be used to prevent the page from auto jumping to form confirmation upon form submission
// add_filter( 'gform_confirmation_anchor', '__return_false' );

/* -- BlogVault -- */

// Hide Blogvault from non @freshysites.com users
function fs_check_blogvault_is_active() {
	// Check if Blogvault plugin is active
	if ( is_plugin_active( 'blogvault-real-time-backup/blogvault.php' ) ) {
		// hide it from the Plugins list table
		add_filter('all_plugins', 'fs_remove_blogvault_admin_plugin_list');
		function fs_remove_blogvault_admin_plugin_list($plugins) {
			// get current user data
			$current_user = wp_get_current_user(); 
			// get their email address
			$user_email = $current_user->user_email;
			// ** SET LIST OF ALLOWED EMAILS **
			$allowed_emails = array('wp@freshysites.com', 'wp+forbes@freshysites.com', 'fswebadmin@forbesbooks.com');
			// if the current user email is not withinout list of $allowed_emails, then they are deemed a user with $banned_user_email
			$banned_user_email = !in_array($user_email, $allowed_emails);
			// if the current user is a banned email, remove the BV plugin form the list
			if ( $banned_user_email ) {
				unset($plugins['blogvault-real-time-backup/blogvault.php']);
			}
			return $plugins;
		}
		// hide it from the Admin sidebar menu
		add_action('admin_init', 'fs_remove_blogvault_admin_menu_links', 999);
		function fs_remove_blogvault_admin_menu_links() {
			// get current user data
			$current_user = wp_get_current_user(); 
			// get their email address
			$user_email = $current_user->user_email;
			// ** SET LIST OF ALLOWED EMAILS **
			$allowed_emails = array('wp@freshysites.com', 'wp+forbes@freshysites.com', 'fswebadmin@forbesbooks.com');
			// if the current user email is not withinout list of $allowed_emails, then they are deemed a user with $banned_user_email
			$banned_user_email = !in_array($user_email, $allowed_emails);
			// if the current user is a banned email, remove the BV menu from sidebar
			if ( $banned_user_email ) {
				remove_menu_page('bvbackup');
			}
		}
	}
}
add_action( 'admin_init', 'fs_check_blogvault_is_active' );

/* -- All in One SEO Pack -- */

// disable the SEO menu in the admin toolbar
add_filter( 'aioseo_show_in_admin_bar', '__return_false' );
// disable the AIOSEO Details column for users that don't have a certain email address
// https://wordpress.org/support/topic/remove-aioseo-details-via-remove_action/#post-16434394
add_action( 'current_screen', 'remove_aioseo_column', 0 );
function remove_aioseo_column() {

	// get current user data
	$current_user = wp_get_current_user(); 

	// get their email address
	$user_email = $current_user->user_email;

	// ** SET LIST OF ALLOWED EMAILS **
	$allowed_emails = array('wp@freshysites.com', 'wp+forbes@freshysites.com', 'fswebadmin@forbesbooks.com');

	// if the current user email is not within our list of $allowed_emails, then they are deemed a user with $banned_user_email
	$banned_user_email = !in_array($user_email, $allowed_emails);

	// get allt he actions and filters
	global $wp_filter;

	// if it's current screen isn't set, then get out of the function
	if ( empty( $wp_filter['current_screen'][1] ) ) {
		return;
	}
	// go through each current screen details
	foreach ( $wp_filter['current_screen'][1] as $actionName => $params ) {
		if (
			empty( $params['function'][0] ) ||
			! is_object( $params['function'][0] ) ||
			stripos( get_class( $params['function'][0] ), 'aioseo' ) === false
		) {
			continue;
		}
		// if user is banned, then hide the AIOSEO Details screen option (and thus the AIOSEO column)
		if ( $banned_user_email ) {
			remove_action( 'current_screen', $params['function'], 1 );
		}
	}

}

/* -- Plugin Notes Plus -- */

// Remove the Plugin Notes Plus data for all users other than the ones we approve below
// based on their user email domain, or their user ID
// using their own filter, we hide its output
add_filter( 'plugin-notes-plus_hide_notes', 'fs_remove_plugin_notes_data_for_most_users' );
function fs_remove_plugin_notes_data_for_most_users( $hide_notes ) {

	// get current user data
	$current_user = wp_get_current_user(); 

	// get their email address
	$user_email = $current_user->user_email;

	// ** SET LIST OF ALLOWED EMAILS **
	$allowed_emails = array('wp@freshysites.com', 'wp+forbes@freshysites.com', 'fswebadmin@forbesbooks.com');

	// if the current user email is not withinout list of $allowed_emails, then they are deemed a user with $banned_user_email
	$banned_user_email = !in_array($user_email, $allowed_emails);

	// if the current user is a banned email, then hide the plugin data output
	if ( $banned_user_email ) {

		// then hide the Plugin Notes Plus output
		$hide_notes = true;

	}    

	// return the notes (unless we are hiding them, based on above logic)
	return $hide_notes;

}
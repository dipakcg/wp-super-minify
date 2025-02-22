<?php
/*
Plugin Name: WP Super Minify
Plugin URI: https://github.com/dipakcg/wp-super-minify
Description: Smartly minify, compress and cache HTML, CSS & JavaScript files to boost website speed. 🚀
Version: 2.0.1
Author: Dipak C. Gajjar
Author URI: https://dipakgajjar.com
*/

// Define plugin version for future releases
if ( !defined ('WPSMY_PLUGIN_VERSION_NUM' ) ) {
    define( 'WPSMY_PLUGIN_VERSION_NUM', '2.0.1' );
}
if ( !defined ('WPSMY_MINIFY_LIBRARY_PATH' ) ) {
	define( 'WPSMY_MINIFY_LIBRARY_PATH', plugin_dir_path( __FILE__ ) . 'includes/min' );
}
if ( !defined ('WPSMY_CACHE_DIR' ) ) {
	define( 'WPSMY_CACHE_DIR',  WP_CONTENT_DIR . '/cache/wp-super-minify/' );
}
if ( !defined ('WPSMY_CSS_CACHE_DIR' ) ) {
	define( 'WPSMY_CSS_CACHE_DIR', WPSMY_CACHE_DIR . 'css/' );
}
if ( !defined ('WPSMY_JS_CACHE_DIR' ) ) {
	define( 'WPSMY_JS_CACHE_DIR', WPSMY_CACHE_DIR . 'js/' );
}

require_once( plugin_dir_path( __FILE__ ) . 'clear-minified-cache.php' );
require_once( plugin_dir_path( __FILE__ ) . 'rating-support.php' );

// require_once( WPSMY_MINIFY_LIBRARY_PATH . "/src/" . strtoupper($type) . '.php' );
require_once( WPSMY_MINIFY_LIBRARY_PATH . "/src/Minify.php" );
require_once( WPSMY_MINIFY_LIBRARY_PATH . "/src/CSS.php" );
require_once( WPSMY_MINIFY_LIBRARY_PATH . "/src/JS.php" );
require_once( WPSMY_MINIFY_LIBRARY_PATH . "/../path-converter/ConverterInterface.php" );
require_once( WPSMY_MINIFY_LIBRARY_PATH . '/../path-converter/Converter.php' );

// Create Cache directories and sub-directories for CSS and JS
if ( !file_exists( WPSMY_CACHE_DIR ) ) mkdir( WPSMY_CACHE_DIR, 0755, true );
if ( !file_exists( WPSMY_CSS_CACHE_DIR ) ) mkdir( WPSMY_CSS_CACHE_DIR, 0755, true );
if ( !file_exists( WPSMY_JS_CACHE_DIR ) ) mkdir( WPSMY_JS_CACHE_DIR, 0755, true );

// Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
add_action( 'admin_init', 'wpsmy_add_stylesheet' );
function wpsmy_add_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'wpsmy-stylesheet', plugins_url('assets/css/style.min.css', __FILE__) );
    wp_enqueue_style( 'wpsmy-stylesheet' );
	
	// Hook 'Review this plugin'
	do_action( 'wpsmy_rating_system_action' );
}

// Register admin menu
add_action( 'admin_menu', 'wpsmy_add_admin_menu' );
function wpsmy_add_admin_menu() {
	// add_menu_page( 'WP Super Minify Settings', 'WP Super Minify', 'manage_options', 'wp-super-minify', 'wpsmy_admin_options', plugins_url('assets/images/wpsmy-icon-24x24.png', __FILE__) );
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page( 'WP Super Minify', 'WP Super Minify', 'manage_options', 'wp-super-minify', 'wpsmy_admin_options' );
}

// Add settings link on plugin page
function wpsmy_settings_link( $links ) {
	// $settings_link = '<a href="admin.php?page=wp-performance-score-booster">Settings</a>';
	$settings_link = '<a href="options-general.php?page=wp-super-minify">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wpsmy_settings_link' );

// Adding WordPress plugin meta links
function wpsmy_plugin_meta_links( $links, $file ) {
	$plugin = plugin_basename( __FILE__ );
	// Create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="https://dipakgajjar.com/products/wordpress-speed-optimisation-service?utm_source=plugins%20page&utm_medium=text%20link&utm_campaign=wordplress%20plugins" style="color:#FF0000;" target="_blank">Order Pro-Level WordPress Speed Optimisation Services</a>' )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'wpsmy_plugin_meta_links', 10, 2 );

// Admin options/setting page
function wpsmy_admin_options() {
	?>
	<div class="wrap">
	<table width="100%" border="0">
	<tr>
	<td width="75%">
	<h2>
		<img src="<?php echo plugins_url( 'assets/images/wpsmy-icon-24x24.png', __FILE__ ); ?>" 
			 style="vertical-align: middle; width="24" height="24"> 
		WP Super Minify : Settings
	</h2>
	<hr />
	<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// Variables for the field and option names
	$hidden_field_name = 'wpsmy_submit_hidden';
    $combine_js = 'wpsmy_combine_js';
    $combine_css = 'wpsmy_combine_css';

    // Read in existing option value from database
    $combine_js_val = get_option($combine_js);
    $combine_css_val = get_option($combine_css);

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset( $_POST[$hidden_field_name] ) && $_POST[$hidden_field_name] == 'Y' ) {
		// CSRF Check
    	if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpsmy_settings_nonce' ) ) {
			if ( isset( $_POST['wpsmy_clear_minified'] ) ) {
				wpsmy_clear_minified_cache();
			}
			else {			
				// Read their posted value
				$combine_js_val = ( isset( $_POST[$combine_js] ) ? sanitize_text_field( $_POST[$combine_js] ) : "" );
				$combine_css_val = ( isset( $_POST[$combine_css] ) ? sanitize_text_field( $_POST[$combine_css] ) : "" );
	
				// Save the posted value in the database
				update_option( $combine_js, $combine_js_val );
				update_option( $combine_css, $combine_css_val );
	
				echo '<div class="updated"><p><strong>Settings Saved.</strong></p></div>';
			}
		}
	}
	?>
	<form method="post" name="options_form">
	<?php wp_nonce_field( 'wpsmy_settings_nonce' ); ?>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
    <p>
    <input type="checkbox" name="<?php echo $combine_css; ?>" id="<?php echo $combine_css; ?>" <?php checked( $combine_css_val == 'on',true); ?> />
    <label for="<?php echo $combine_css; ?>" class="wpsmy_settings" style="display: inline;"> <?php _e('Optimise CSS'); ?> </label>
    </p>
	<p>
	<input type="checkbox" name="<?php echo $combine_js; ?>" id="<?php echo $combine_js; ?>" <?php checked( $combine_js_val == 'on',true); ?> />
	<label for="<?php echo $combine_js; ?>" class="wpsmy_settings" style="display: inline;"> <?php _e('Optimise JavaScript'); ?> </label>
	</p>
    <p><input type="submit" value="<?php esc_attr_e('Save Changes') ?>" class="button button-primary" name="submit" />
	<button type="submit" name="wpsmy_clear_minified" class="button button-primary">Clear CSS & JS Cache</button>
    </p>
	</form>
	<p> &nbsp; </p>
	<?php
	$message = "";
	$css_enabled = ($combine_css_val == 'on');
	$js_enabled = ($combine_js_val == 'on');
	
	$templates = [
		'css_js'	=>	"✅ WP Super Minify now minifies, compresses, and caches all %s files. Enable '<em>Optimise %s</em>' to further boost your site's performance.",
		'both'		=>	"✅ WP Super Minify now minifies, compresses, and caches all CSS & JavaScript files — making your site lighter, faster, and more optimised than ever! 🚀",
		'none'		=>	"<span style='color: RED !important;'>❗ You haven’t selected any options above — WP Super Minify isn’t currently optimising your site.
						<br /> <br />If you’re not debugging or troubleshooting errors, consider enabling the options above to boost your site's performance.</span>",
	];
	$hassle_free_updates = "✅ Enjoy a seamless experience — Minified files are automatically updated whenever the original files are modified.";
	
	if ( $js_enabled && !$css_enabled ) {
		$message = sprintf($templates['css_js'], "JavaScript", "CSS") . '<br /> <br />' . $hassle_free_updates;
	} elseif ( $css_enabled && !$js_enabled ) {
		$message = sprintf($templates['css_js'], "CSS", "JS") . '<br /> <br />' . $hassle_free_updates;
	} elseif ( $js_enabled && $css_enabled ) {
		$message = $templates['both'] . '<br /> <br />' . $hassle_free_updates;
	} else {
		$message = $templates['none'];
	}
	// Output the final message
	echo '<p style="color:#00a32a; font-weight: bold;">' . $message . '</p>';
	?>
	</td>
	<td style="text-align: left;">
	<div class="wpsmy_admin_dev_sidebar_div">
	<!-- <img src="//www.gravatar.com/avatar/38b380cf488d8f8c4007cf2015dc16ac.jpg" width="100px" height="100px" /> -->
	<br />
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-support-this-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3S8BRPLWLNQ38" target="_blank"> Donate and support this plugin </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-rate-this-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://wordpress.org/support/view/plugin-reviews/wp-super-minify" target="_blank"> Rate this plugin on WordPress.org </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-wordpress-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://wordpress.org/support/plugin/wp-super-minify" target="_blank"> Get support on WordPress.org </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-github-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="https://github.com/dipakcg/wp-super-minify" target="_blank"> Contribute development on GitHub </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-other-plugins-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://profiles.wordpress.org/dipakcg#content-plugins" target="_blank"> Get my other plugins </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-twitter-16x16.png' , __FILE__ ) . '" > ';  ?>Follow me on Twitter: <a href="https://twitter.com/dipakcgajjar" target="_blank">@dipakcgajjar</a> </span>
	<br />
	<span class="wpsmy_admin_dev_sidebar" style="float: right;"> Version: <strong> <?php echo get_option('wpsmy_plugin_version'); ?> </strong> </span>
	</div>
	</td>
	</tr>
	</table>
	</div>
	<?php
}

// Check if the plugin has been updated and display a review notice if applicable.
function wpsmy_check_plugin_update() {
	$saved_version = get_option('wpsmy_plugin_version' ); // Default version if not set

	/* Display review plugin notice if plugin updated */
	// only applies to older versions of the plugin (older than 2.0.1) where option isn't set
	// As version 2.0 is a major release, let's ask users to submit review on wordpress.org
	if ( version_compare( $saved_version, WPSMY_PLUGIN_VERSION_NUM, '<' ) || $saved_version === FALSE ) {
		// Version is less than 2.0.1, show the review notice
		if ( $saved_version && in_array( $saved_version, ['2.0', '1.6'], true ) ) {
			update_option( 'wpsmy_review_notice', 'on' );
		}
		
		// Update the version in the database to prevent repeated notices
		update_option( 'wpsmy_plugin_version', WPSMY_PLUGIN_VERSION_NUM );
	}
}
add_action( 'admin_init', 'wpsmy_check_plugin_update' );

// Make the default value of enable javascript and enable CSS to true on plugin activation
function wpsmy_activate_plugin() {

    // Save default options value in the database
    update_option( 'wpsmy_combine_js', 'on' );
    update_option( 'wpsmy_combine_css', 'on' );
	
	// Rate this plugin on wordpress.org - check for admin notice dismissal
	if ( FALSE === get_option( 'wpsmy_review_notice' ) ) {
		add_option( 'wpsmy_review_notice', 'on' );
	}
}
register_activation_hook( __FILE__, 'wpsmy_activate_plugin' );

// Remove filters/functions on plugin deactivation
function wpsmy_deactivate_plugin() {
	delete_option( 'wpsmy_plugin_version' );
}
register_deactivation_hook( __FILE__, 'wpsmy_deactivate_plugin' );

function wpsmy_minify_html( $buffer ) {
	$wpsmy_plugin_version = get_option( 'wpsmy_plugin_version' );
	$initial = strlen( $buffer );
	
	// Include Minify libraries only if not already loaded
	if ( !class_exists( 'Minify_HTML' ) ) {
		require_once( WPSMY_MINIFY_LIBRARY_PATH . "/lib/Minify/HTML.php" );
		require_once( WPSMY_MINIFY_LIBRARY_PATH . "/lib/Minify/Loader.php" );
		Minify_Loader::register();
	}

	// Minify inline JavaScript if enabled
	if ( get_option('wpsmy_combine_js', 1 ) === 'on') {
		$buffer = Minify_HTML::minify( $buffer, array(
			'jsMinifier' => array( 'JSMin', 'minify' )
		));
	}

	// Minify inline CSS if enabled
	if (get_option( 'wpsmy_combine_css', 1 ) === 'on') {
		$buffer = Minify_HTML::minify( $buffer, array(
			'cssMinifier' => array( 'Minify_CSS', 'minify' )
		));
	}

	// Calculate savings
	$final = strlen( $buffer );
	if ($initial > 0) {
		$savings = round((($initial - $final) / $initial * 100), 3);
	} else {
		$savings = 0; // Avoid division by zero
	}

	// Store the comment in a global variable instead of appending to the buffer
	if ( $savings > 0 ) {
		global $wpsmy_minify_comment;
		$wpsmy_minify_comment = PHP_EOL . '<!--' . PHP_EOL . 
			'*** This site runs WP Super Minify plugin v' . esc_html($wpsmy_plugin_version) . ' - http://wordpress.org/plugins/wp-super-minify ***' . PHP_EOL . 
			'*** Total size saved: ' . esc_html($savings) . '% | Size before compression: ' . esc_html($initial) . ' bytes | Size after compression: ' . esc_html($final) . ' bytes. ***' . PHP_EOL . 
			'-->';
	}

	return $buffer;
}

// Start HTML Minification with output buffering
function wpsmy_html_minify_start() {
	if (!is_admin() && !defined('DOING_AJAX')) {
		ob_start('wpsmy_minify_html');
	}
}
add_action( 'template_redirect', 'wpsmy_html_minify_start', 1 );

// Flush the buffer at the end of the page
function wpsmy_html_minify_end() {
	if (!is_admin() && ob_get_length()) {
		ob_end_flush();
	}
}
add_action( 'shutdown', 'wpsmy_html_minify_end', 9999 );

// Output minification comment at the bottom of the page
function wpsmy_print_minify_comment() {
	global $wpsmy_minify_comment;
	if (!empty($wpsmy_minify_comment)) {
		echo $wpsmy_minify_comment;
	}
}
add_action( 'shutdown', 'wpsmy_print_minify_comment', 10000 );

// Hook to process and replace scripts and styles
add_action( 'wp_enqueue_scripts', 'wpsmy_minify_enqueue_scripts', 999 );
function wpsmy_minify_enqueue_scripts() {
	global $wp_styles, $wp_scripts;

	// Minify and replace styles
	if ( !empty( $wp_styles->queue ) && get_option( 'wpsmy_combine_css', 1 ) === 'on' ) {
		foreach ($wp_styles->queue as $handle) {
			$src = $wp_styles->registered[$handle]->src;
			if ($src) {
				$minified_src = wpsmy_minify_file($src, 'css');
				if ($minified_src) {
					$wp_styles->registered[$handle]->src = $minified_src;
				}
			}
		}
	}

	// Minify and replace scripts
	if ( !empty( $wp_scripts->queue ) && get_option( 'wpsmy_combine_js', 1 ) === 'on' ) {
		foreach ($wp_scripts->queue as $handle) {
			$src = $wp_scripts->registered[$handle]->src;
			if ($src) {
				$minified_src = wpsmy_minify_file($src, 'js');
				if ($minified_src) {
					$wp_scripts->registered[$handle]->src = $minified_src;
				}
			}
		}
	}
}

/**
 * Minifies a given CSS or JS file and stores it.
 *
 * @param string $file_url URL of the original file.
 * @param string $type 'css' or 'js'.
 * @return string|false Minified file URL or false if failed.
 */
function wpsmy_minify_file( $file_url, $type ) {
	
	if ( strpos($file_url, '.min.') !== false ) {
		return $file_url; // Skip if already minified
	}

	$cache_filetype_dir = ( $type === 'js' ? 'js/' : 'css/' );
	$cache_url = content_url() . '/cache/wp-super-minify/';

	$file_path = str_replace(home_url(), ABSPATH, $file_url);
	$minified_file_name = md5($file_url) . '.' . $type;
	$minified_file_path = WPSMY_CACHE_DIR . $cache_filetype_dir . $minified_file_name;
	$minified_file_url = $cache_url . $cache_filetype_dir . $minified_file_name;
	$hash_file_path = $minified_file_path . '.hash'; // Store file hash

	// Check if original file exists
	if ( !file_exists( $file_path ) ) {
		return false; // Skip if file is not local
	}
	
	// Get current file hash
	$current_hash = md5_file($file_path);
	
	// Check if minified file exists and hash matches
	if ( file_exists($minified_file_path) && file_exists($hash_file_path) ) {
		$saved_hash = file_get_contents( $hash_file_path );
		if ( $saved_hash === $current_hash ) {
			return $minified_file_url; // Return existing minified file
		}
	}

	try {
		if ( $type === 'css' ) {
			$minifier = new MatthiasMullie\Minify\CSS( $file_path );
		} elseif ( $type === 'js' ) {
			$minifier = new MatthiasMullie\Minify\JS( $file_path );
		} else {
			return false;
		}

		$minifier->minify( $minified_file_path );
		
		// Store hash to track changes
		file_put_contents($hash_file_path, $current_hash);

		// Store in options table
		$stored_files = get_option( 'wpsmy_minified_files', [] );
		$stored_files[$file_url] = $minified_file_url;
		update_option( 'wpsmy_minified_files', $stored_files );

		return $minified_file_url;
	} catch ( Exception $e ) {
		return false;
	}
}

/* END OF PLUGIN */
?>

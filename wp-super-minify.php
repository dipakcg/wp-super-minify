<?php
/*
Plugin Name: WP Super Minify
Plugin URI: https://github.com/dipakcg/wp-super-minify
Description: Minifies, caches and combine inline JavaScript and CSS files to improve page load time.
Version: 1.5
Author: Dipak C. Gajjar
Author URI: https://dipakgajjar.com
*/

// Define plugin version for future releases
if (!defined('WPSMY_PLUGIN_VERSION')) {
    define('WPSMY_PLUGIN_VERSION', 'wpsmy_plugin_version');
}
if (!defined('WPSMY_PLUGIN_VERSION_NUM')) {
    define('WPSMY_PLUGIN_VERSION_NUM', '1.5');
}
update_option(WPSMY_PLUGIN_VERSION, WPSMY_PLUGIN_VERSION_NUM);

// Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
add_action( 'admin_init', 'wpsmy_add_stylesheet' );
function wpsmy_add_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'wpsmy-stylesheet', plugins_url('assets/css/style.min.css', __FILE__) );
    wp_enqueue_style( 'wpsmy-stylesheet' );
}

// Register admin menu
add_action( 'admin_menu', 'wpsmy_add_admin_menu' );
function wpsmy_add_admin_menu() {
	// add_menu_page( 'WP Super Minify Settings', 'WP Super Minify', 'manage_options', 'wp-super-minify', 'wpsmy_admin_options', plugins_url('assets/images/wpsmy-icon-24x24.png', __FILE__) );
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page( 'WP Super Minify', 'WP Super Minify', 'manage_options', 'wp-super-minify', 'wpsmy_admin_options' );
}

// Add settings link on plugin page
function wpsmy_settings_link($links) {
	// $settings_link = '<a href="admin.php?page=wp-performance-score-booster">Settings</a>';
	$settings_link = '<a href="options-general.php?page=wp-super-minify">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wpsmy_settings_link' );

// Adding WordPress plugin meta links
function wpsmy_plugin_meta_links( $links, $file ) {
	$plugin = plugin_basename(__FILE__);
	// Create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="https://dipakgajjar.com/products/wordpress-speed-optimisation-service?utm_source=plugins%20page&utm_medium=text%20link&utm_campaign=wordplress%20plugins" style="color:#FF0000;" target="_blank">Order WordPress Speed Optimisation Service</a>' )
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
	<h2><?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-icon-24x24.png' , __FILE__ ) . '" > ';  ?> WP Super Minify : Settings</h2>
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
    if( isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y' ) {
        // Read their posted value
        $combine_js_val = (isset($_POST[$combine_js]) ? $_POST[$combine_js] : "");
        $combine_css_val = (isset($_POST[$combine_css]) ? $_POST[$combine_css] : "");

        // Save the posted value in the database
        update_option( $combine_js, $combine_js_val );
        update_option( $combine_css, $combine_css_val );

        // Put an settings updated message on the screen
   	?>
   	<div class="updated"><p><strong>Settings Saved.</strong></p></div>
	<?php
	}
	?>
	<form method="post" name="options_form">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<p>
	<input type="checkbox" name="<?php echo $combine_js; ?>" id="<?php echo $combine_js; ?>" <?php checked( $combine_js_val == 'on',true); ?> />
	<label for="<?php echo $combine_js; ?>" class="wpsmy_settings" style="display: inline;"> <?php _e('Compress JavaScript'); ?> </label>
	</p>
    <p>
    <input type="checkbox" name="<?php echo $combine_css; ?>" id="<?php echo $combine_css; ?>" <?php checked( $combine_css_val == 'on',true); ?> />
    <label for="<?php echo $combine_css; ?>" class="wpsmy_settings" style="display: inline;"> <?php _e('Compress CSS'); ?> </label>
    </p>
    <p><input type="submit" value="<?php esc_attr_e('Save Changes') ?>" class="button button-primary" name="submit" />
    </p>
    </form>
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
	<hr style="margin: 2em 0 1.5em 0;" />
	<?php
	// Promo - Ad contents
	$promo_content = wp_remote_fopen("https://cdn.rawgit.com/dipakcg/wp-performance-score-booster/master/promos.html");
    echo $promo_content;
	?>
	<?php // Bottom - News and Referrals part ?>
	<hr style="margin: 1.5em 0 2em 0;" />
    <table cellspacing="0" cellpadding="0" class="wpsmy_news_section"> <tr>
    <td width="49%" valign="top">
    <h2><strong>News & Updates from Dipak C. Gajjar</strong></h2>
    <hr />
    <div class="wpsmy_rss-widget">
	<?php
    /* wp_widget_rss_output(array(
          'url' => 'https://dipakgajjar.com/category/news/feed/?refresh='.rand(10,100).'',  // feed URL
          'title' => 'News & Updates from Dipak C. Gajjar',
          'items' => 3, // nubmer of posts to display
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 0
     )); */
     /* Load the news content from Github */
    $news_content = wp_remote_fopen("https://cdn.rawgit.com/dipakcg/wp-performance-score-booster/master/news-and-updates.html");
    echo $news_content;
    ?>
	</div> </td>
	<!-- Referrals -->
	<td width="1%"> &nbsp </td>
	<td width="50%" valign="top">
	<div class="wpsmy_referrals">
		Scalable and affordable SSD VPS at DigitalOcean starting from $5 per month. <br /> <br />
		<a href="https://www.digitalocean.com" target="_blank" onClick="this.href='https://m.do.co/c/f90a24a27dcc'" ><img src="https://dl.dropboxusercontent.com/u/21966579/do-ssd-virtual-servers-250x250.jpg" alt="Digital Ocean SSD VPS" width="250" height="250" border="0"></a>
	</div>
	<div class="wpsmy_referrals">
		Great managed WordPress hosting at SiteGround starting from $3.95 per month. <br /> <br />
		<a href="http://www.siteground.com" target="_blank" onClick="this.href='https://www.siteground.com/wordpress-hosting.htm?afbannercode=783dd6fb6802e26ada6cf20768622fda'" ><img src="https://ua.siteground.com/img/banners/general/best-pack/250x250.gif" alt="WordPress Hosting" width="250" height="250" border="0"></a>
	</div>
	<?php echo '</td> </tr> </table>'; ?>
	<?php
}

// Make the default value of enable javascript and enable CSS to true on plugin activation
function wpsmy_activate_plugin() {

    // Save default options value in the database
    update_option( 'wpsmy_combine_js', 'on' );
    update_option( 'wpsmy_combine_css', 'on' );
}
register_activation_hook( __FILE__, 'wpsmy_activate_plugin' );

// Remove filters/functions on plugin deactivation
function wpsmy_deactivate_plugin() {
	delete_option( 'wpsmy_plugin_version' );
}
register_deactivation_hook( __FILE__, 'wpsmy_deactivate_plugin' );

function wpsmy_minify_html ($buffer) {
	$wpsmy_plugin_version = get_option('wpsmy_plugin_version');
    /* if ( is_user_logged_in() ) {
        $buffer .= PHP_EOL . '<!--' . PHP_EOL . '*** This site runs WP Super Minify plugin v'. $wpsmy_plugin_version . ' - http://wordpress.org/plugins/wp-super-minify ***' . PHP_EOL . '*** User is logged in, compression is not applied. ***' . PHP_EOL . '-->';
        return $buffer; // for loggedin users minify is not required
    } else { */
        $initial = strlen($buffer);
        $minify_lib_path = plugin_dir_path( __FILE__ ) . 'includes/min';

        if (!class_exists('Minify_HTML')) {
			require_once("$minify_lib_path/lib/Minify/HTML.php");
			ini_set('include_path', ini_get('include_path').":$minify_lib_path/lib");
			require_once("$minify_lib_path/lib/Minify/CSS.php");
			require_once("$minify_lib_path/lib/JSMin.php");
			require ("$minify_lib_path/lib/Minify/Loader.php");
			Minify_Loader::register();
		}
		if ( get_option('wpsmy_combine_js', 1) == 'on') {
			$buffer = Minify_HTML::minify($buffer,
				  array('jsMinifier' => array('JSMin', 'minify')));
		}
		if ( get_option('wpsmy_combine_css', 1) == 'on') {
			$buffer = Minify_HTML::minify($buffer,
				  array('cssMinifier' => array('Minify_CSS', 'minify')));
		}

		$final = strlen($buffer);
		$savings = round((($initial-$final)/$initial*100), 3);

		// $buffer .= "<br/><!-- Uncompressed size: $initial bytes; Compressed size: $final bytes; $savings% savings -->";

		if ($savings != 0) {
			$buffer .= PHP_EOL . '<!--' . PHP_EOL . '*** This site runs WP Super Minify plugin v'. $wpsmy_plugin_version .' - http://wordpress.org/plugins/wp-super-minify ***' . PHP_EOL . '*** Total size saved: ' . $savings . '% | Size before compression: ' . $initial . ' bytes | Size after compression: ' . $final . ' bytes. ***' . PHP_EOL . '-->';
		}

        return $buffer;
    // }
}

// Minifying HTML
function wpsmy_minify() {
    ob_start('wpsmy_minify_html');
}
add_action('get_header', 'wpsmy_minify');

/* END OF PLUGIN */
?>

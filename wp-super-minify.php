<?php
/*
Plugin Name: WP Super Minify
Plugin URI: https://github.com/dipakcg/wp-super-minify
Description: Minifies, caches and combine inline JavaScript and CSS files to improve page load time.
Version: 1.3.1
Author: Dipak C. Gajjar
Author URI: https://dipakgajjar.com
*/

// Define plugin version for future releases
if (!defined('WPSMY_PLUGIN_VERSION')) {
    define('WPSMY_PLUGIN_VERSION', 'wpsmy_plugin_version');
}
if (!defined('WPSMY_PLUGIN_VERSION_NUM')) {
    define('WPSMY_PLUGIN_VERSION_NUM', '1.3.1');
}
update_option(WPSMY_PLUGIN_VERSION, WPSMY_PLUGIN_VERSION_NUM);

// Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
add_action( 'admin_init', 'wpsmy_add_stylesheet' );
function wpsmy_add_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'wpsmy-stylesheet', plugins_url('assets/css/style.css', __FILE__) );
    wp_enqueue_style( 'wpsmy-stylesheet' );
}

// Register admin menu
add_action( 'admin_menu', 'wpsmy_add_admin_menu' );
function wpsmy_add_admin_menu() {
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_menu_page( 'WP Super Minify Settings', 'WP Super Minify', 'manage_options', 'wp-super-minify', 'wpsmy_admin_options', plugins_url('assets/images/wpsmy-icon-24x24.png', __FILE__) );
}

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
	<input type="checkbox" name="<?php echo $combine_js; ?>" <?php checked( $combine_js_val == 'on',true); ?> /> &nbsp; <span class="wpsmy_settings"> Compress JavaScript </span>
	</p>
    <p>
    <input type="checkbox" name="<?php echo $combine_css; ?>" <?php checked( $combine_css_val == 'on',true); ?> /> &nbsp; <span class="wpsmy_settings"> Compress CSS </span>
    </p>
    <p><input type="submit" value="<?php esc_attr_e('Save Changes') ?>" class="button button-primary" name="submit" />
    </p>
    </form>
	</td>
	<td style="text-align: left;">
	<div class="wpsmy_admin_dev_sidebar_div">
	<img src="//www.gravatar.com/avatar/38b380cf488d8f8c4007cf2015dc16ac.jpg" width="100px" height="100px" /> <br />
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-support-this-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3S8BRPLWLNQ38" target="_blank"> Support this plugin and donate </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-rate-this-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://wordpress.org/support/view/plugin-reviews/wp-super-minify" target="_blank"> Rate this plugin on WordPress.org </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-wordpress-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://wordpress.org/support/plugin/wp-super-minify" target="_blank"> Get support on on WordPress.org </a> </span>
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
	echo '<hr style="margin-bottom: 2em;" />';
    echo '<table cellspacing="0" cellpadding="0" class="news_section"> <tr>';
    echo '<td width="50%" valign="top">';
    echo '<h1>News & Updates from Dipak C. Gajjar</h1>';
    echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'https://dipakgajjar.com/category/news/feed/?refresh='.rand(10,100).'',  // feed URL
          'title' => 'News & Updates from Dipak C. Gajjar',
          'items' => 3, // nubmer of posts to display
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 0
     ));
     echo '</div> <td width="5%"> &nbsp </td>';
     echo '</td> <td valign="top">';
     ?>
     <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/dipakcgajjar" data-widget-id="547661367281729536">Tweets by @dipakcgajjar</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<?php echo '</td> </tr> </table>';
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

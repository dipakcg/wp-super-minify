<?php
/*
Plugin Name: WP Super Minify
Plugin URI: https://github.com/dipakcg/wp-super-minify
Description: Minifies, caches and combine JavaScript and CSS files into a single file to improve page load time.
Version: 1.3
Author: Dipak C. Gajjar
Author URI: http://dipakgajjar.com
*/

// Define plugin version for future releases
if (!defined('WPSMY_PLUGIN_VERSION')) {
    define('WPSMY_PLUGIN_VERSION', 'wpsmy_plugin_version');
}
if (!defined('WPSMY_PLUGIN_VERSION_NUM')) {
    define('WPSMY_PLUGIN_VERSION_NUM', '1.3');
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

/* minit code ( https://github.com/kasparsd/minit ) */
$wpsmy_instance = Wpsmy::instance();
class Wpsmy {
	static $instance;
	private $wpsmy_done = array();
	private $async_queue = array();
	private function __construct() {
		add_filter( 'print_scripts_array', array( $this, 'init_wpsmy_js' ) );
		add_filter( 'print_styles_array', array( $this, 'init_wpsmy_css' ) );
		// Print external scripts asynchronously in the footer
		add_action( 'wp_print_footer_scripts', array( $this, 'async_init' ), 5 );
		add_action( 'wp_print_footer_scripts', array( $this, 'async_print' ), 20 );
	}
	public static function instance() {
		if ( ! self::$instance )
			self::$instance = new Wpsmy();
		return self::$instance;
	}
	function init_wpsmy_js( $todo ) {
		global $wp_scripts;
		return $this->wpsmy_objects( $wp_scripts, $todo, 'js' );
	}
	function init_wpsmy_css( $todo ) {
		global $wp_styles;
		return $this->wpsmy_objects( $wp_styles, $todo, 'css' );
	}
	function wpsmy_objects( &$object, $todo, $extension ) {
		// Don't run if on admin or already processed
		if ( is_admin() || empty( $todo ) )
			return $todo;
		// Allow files to be excluded from wpsmy
		$wpsmy_exclude = (array) apply_filters( 'wpsmy-exclude-' . $extension, array() );
		// Exluce all wpsmy items by default
		$wpsmy_exclude = array_merge( $wpsmy_exclude, $this->get_done() );
		$wpsmy_todo = array_diff( $todo, $wpsmy_exclude );
		if ( empty( $wpsmy_todo ) )
			return $todo;
		$done = array();
		$ver = array();
		// Bust cache on wpsmy plugin update
		$ver[] = 'wpsmy-ver-' . WPSMY_PLUGIN_VERSION;
		// Debug enable
		// $ver[] = 'debug-' . time();
		// Use different cache key for SSL and non-SSL
		$ver[] = 'is_ssl-' . is_ssl();
		// Use a global cache version key to purge cache
		$ver[] = 'wpsmy_cache_ver-' . get_option( 'wpsmy_cache_ver' );
		// Use script version to generate a cache key
		foreach ( $wpsmy_todo as $t => $script )
			$ver[] = sprintf( '%s-%s', $script, $object->registered[ $script ]->ver );
		$cache_ver = md5( 'wpsmy-' . implode( '-', $ver ) . $extension );
		// Try to get queue from cache
		$cache = get_transient( 'wpsmy-' . $cache_ver );
		if ( isset( $cache['cache_ver'] ) && $cache['cache_ver'] == $cache_ver && file_exists( $cache['file'] ) )
			return $this->wpsmy_enqueue_files( $object, $cache );
		foreach ( $wpsmy_todo as $script ) {
			// Get the relative URL of the asset
			$src = self::get_asset_relative_path(
					$object->base_url,
					$object->registered[ $script ]->src
				);
			// Add support for pseudo packages such as jquery which return src as empty string
			if ( empty( $object->registered[ $script ]->src ) || '' == $object->registered[ $script ]->src )
				$done[ $script ] = null;
			// Skip if the file is not hosted locally
			if ( ! $src || ! file_exists( ABSPATH . $src ) )
				continue;
			$script_content = apply_filters(
					'wpsmy-item-' . $extension,
					file_get_contents( ABSPATH . $src ),
					$object,
					$script
				);
			if ( false !== $script_content )
				$done[ $script ] = $script_content;
		}
		if ( empty( $done ) )
			return $todo;
		$wp_upload_dir = wp_upload_dir();
		// Try to create the folder for cache
		if ( ! is_dir( $wp_upload_dir['basedir'] . '/wp-super-minify' ) )
			if ( ! mkdir( $wp_upload_dir['basedir'] . '/wp-super-minify' ) )
				return $todo;
		$combined_file_path = sprintf( '%s/wp-super-minify/%s.%s', $wp_upload_dir['basedir'], $cache_ver, $extension );
		$combined_file_url = sprintf( '%s/wp-super-minify/%s.%s', $wp_upload_dir['baseurl'], $cache_ver, $extension );
		// Allow other plugins to do something with the resulting URL
		$combined_file_url = apply_filters( 'wpsmy-url-' . $extension, $combined_file_url, $done );
		// Allow other plugins to minify and obfuscate
		$done_imploded = apply_filters( 'wpsmy-content-' . $extension, implode( "\n\n", $done ), $done );
		// Store the combined file on the filesystem
		if ( ! file_exists( $combined_file_path ) )
			if ( ! file_put_contents( $combined_file_path, $done_imploded ) )
				return $todo;
		$status = array(
				'cache_ver' => $cache_ver,
				'todo' => $todo,
				'done' => array_keys( $done ),
				'url' => $combined_file_url,
				'file' => $combined_file_path,
				'extension' => $extension
			);
		// Cache this set of scripts for 24 hours
		set_transient( 'wpsmy-' . $cache_ver, $status, 24 * 60 * 60 );
		$this->set_done( $cache_ver );
		return $this->wpsmy_enqueue_files( $object, $status );
	}
	function wpsmy_enqueue_files( &$object, $status ) {
		extract( $status );
		switch ( $extension ) {
			case 'css':
				wp_enqueue_style(
					'wpsmy-' . $cache_ver,
					$url,
					null,
					null
				);
				// Add inline styles for all minified styles
				foreach ( $done as $script ) {
					$inline_style = $object->get_data( $script, 'after' );
					if ( empty( $inline_style ) )
						continue;
					if ( is_string( $inline_style ) )
						$object->add_inline_style( 'wpsmy-' . $cache_ver, $inline_style );
					elseif ( is_array( $inline_style ) )
						$object->add_inline_style( 'wpsmy-' . $cache_ver, implode( ' ', $inline_style ) );
				}
				break;
			case 'js':
				wp_enqueue_script(
					'wpsmy-' . $cache_ver,
					$url,
					null,
					null,
					apply_filters( 'wpsmy-js-in-footer', true )
				);
				// Add to the correct
				$object->set_group(
					'wpsmy-' . $cache_ver,
					false,
					apply_filters( 'wpsmy-js-in-footer', true )
				);
				$inline_data = array();
				// Add inline scripts for all minified scripts
				foreach ( $done as $script )
					$inline_data[] = $object->get_data( $script, 'data' );
				// Filter out empty elements
				$inline_data = array_filter( $inline_data );
				if ( ! empty( $inline_data ) )
					$object->add_data( 'wpsmy-' . $cache_ver, 'data', implode( "\n", $inline_data ) );
				break;
			default:
				return $todo;
		}
		// Remove scripts that were merged
		$todo = array_diff( $todo, $done );
		$todo[] = 'wpsmy-' . $cache_ver;
		// Mark these items as done
		$object->done = array_merge( $object->done, $done );
		// Remove wpsmy items from the queue
		$object->queue = array_diff( $object->queue, $done );
		return $todo;
	}
	function set_done( $handle ) {
		$this->wpsmy_done[] = 'wpsmy-' . $handle;
	}
	function get_done() {
		return $this->wpsmy_done;
	}
	public static function get_asset_relative_path( $base_url, $item_url ) {
		// Remove protocol reference from the local base URL
		$base_url = preg_replace( '/^(https?:\/\/|\/\/)/i', '', $base_url );
		// Check if this is a local asset which we can include
		$src_parts = explode( $base_url, $item_url );
		// Get the trailing part of the local URL
		$maybe_relative = end( $src_parts );
		if ( ! file_exists( ABSPATH . $maybe_relative ) )
			return false;
		return $maybe_relative;
	}
	public function async_init() {
		global $wp_scripts;
		if ( ! is_object( $wp_scripts ) || empty( $wp_scripts->queue ) )
			return;
		$base_url = site_url();
		$wpsmy_exclude = (array) apply_filters( 'wpsmy-exclude-js', array() );
		foreach ( $wp_scripts->queue as $handle ) {
			// Skip asyncing explicitly excluded script handles
			if ( in_array( $handle, $wpsmy_exclude ) ) {
				continue;
			}
			$script_relative_path = wpsmy::get_asset_relative_path(
				$base_url,
				$wp_scripts->registered[$handle]->src
			);
			if ( ! $script_relative_path ) {
				// Add this script to our async queue
				$this->async_queue[] = $handle;
				// Remove this script from being printed the regular way
				wp_dequeue_script( $handle );
			}
		}
	}
	public function async_print() {
		global $wp_scripts;
		if ( empty( $this->async_queue ) )
			return;
		?>
		<!-- Asynchronous scripts by wpsmy -->
		<script id="wpsmy-async-scripts" type="text/javascript">
		(function() {
			var js, fjs = document.getElementById('wpsmy-async-scripts'),
				add = function( url, id ) {
					js = document.createElement('script');
					js.type = 'text/javascript';
					js.src = url;
					js.async = true;
					js.id = id;
					fjs.parentNode.insertBefore(js, fjs);
				};
			<?php
			foreach ( $this->async_queue as $handle ) {
				printf(
					'add("%s", "%s"); ',
					$wp_scripts->registered[$handle]->src,
					'async-script-' . esc_attr( $handle )
				);
			}
			?>
		})();
		</script>
		<?php
	}
}

// Prepend the filename of the file being included
if ( get_option('wpsmy_combine_css', 1) == 'on') {
	add_filter( 'wpsmy-item-css', 'wpsmy_comment_combined', 15, 3 );
}
if ( get_option('wpsmy_combine_js', 1) == 'on') {
	add_filter( 'wpsmy-item-js', 'wpsmy_comment_combined', 15, 3 );
}
function wpsmy_comment_combined( $content, $object, $script ) {
	if ( ! $content )
		return $content;
	return sprintf(
			"\n\n/* wpsmy: %s */\n",
			$object->registered[ $script ]->src
		) . $content;
}

// Add table of contents at the top of the wpsmy file
if ( get_option('wpsmy_combine_css', 1) == 'on') {
	add_filter( 'wpsmy-content-css', 'wpsmy_add_toc', 100, 2 );
}
if ( get_option('wpsmy_combine_js', 1) == 'on') {
	add_filter( 'wpsmy-content-js', 'wpsmy_add_toc', 100, 2 );
}
function wpsmy_add_toc( $content, $items ) {
	if ( ! $content || empty( $items ) )
		return $content;
	$toc = array();
	foreach ( $items as $handle => $item_content )
		$toc[] = sprintf( ' - %s', $handle );
	return sprintf( "/* TOC:\n%s\n*/", implode( "\n", $toc ) ) . $content;
}

// Turn all local asset URLs into absolute URLs
if ( get_option('wpsmy_combine_css', 1) == 'on') {
	add_filter( 'wpsmy-item-css', 'wpsmy_resolve_css_urls', 10, 3 );
}
function wpsmy_resolve_css_urls( $content, $object, $script ) {
	if ( ! $content )
		return $content;
	$src = wpsmy::get_asset_relative_path(
			$object->base_url,
			$object->registered[ $script ]->src
		);
	// Make all local asset URLs absolute
	$content = preg_replace(
			'/url\(["\' ]?+(?!data:|https?:|\/\/)(.*?)["\' ]?\)/i',
			sprintf( "url('%s/$1')", $object->base_url . dirname( $src ) ),
			$content
		);
	return $content;
}

// Add support for relative CSS imports
if ( get_option('wpsmy_combine_css', 1) == 'on') {
	add_filter( 'wpsmy-item-css', 'wpsmy_resolve_css_imports', 10, 3 );
}
function wpsmy_resolve_css_imports( $content, $object, $script ) {
	if ( ! $content )
		return $content;
	$src = wpsmy::get_asset_relative_path(
			$object->base_url,
			$object->registered[ $script ]->src
		);
	// Make all import asset URLs absolute
	$content = preg_replace(
			'/@import\s+(url\()?["\'](?!https?:|\/\/)(.*?)["\'](\)?)/i',
			sprintf( "@import url('%s/$2')", $object->base_url . dirname( $src ) ),
			$content
		);
	return $content;
}

// Exclude styles with media queries from being included in wpsmy
if ( get_option('wpsmy_combine_css', 1) == 'on') {
	add_filter( 'wpsmy-item-css', 'wpsmy_exclude_css_with_media_query', 10, 3 );
}
function wpsmy_exclude_css_with_media_query( $content, $object, $script ) {
	if ( ! $content )
		return $content;
	$whitelist = array( '', 'all', 'screen' );
	// Exclude from wpsmy if media query specified
	if ( ! in_array( $object->registered[ $script ]->args, $whitelist ) )
		return false;
	return $content;
}

// Make sure that all wpsmy files are served from the correct protocol
if ( get_option('wpsmy_combine_css', 1) == 'on') {
	add_filter( 'wpsmy-url-css', 'wpsmy_maybe_ssl_url' );
}
if ( get_option('wpsmy_combine_js', 1) == 'on') {
	add_filter( 'wpsmy-url-js', 'wpsmy_maybe_ssl_url' );
}
function wpsmy_maybe_ssl_url( $url ) {
	if ( is_ssl() )
		return str_replace( 'http://', 'https://', $url );
	return $url;
}

// Add a Purge Cache link to the plugin list
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpsmy_cache_purge_admin_link' );
function wpsmy_cache_purge_admin_link( $links ) {
	$links[] = sprintf(
			'<a href="%s">%s</a>',
			wp_nonce_url( add_query_arg( 'purge_wpsmy', true ), 'purge_wpsmy' ),
			__( 'Purge WP Super Minify cache', 'wpsmy' )
		);
	return $links;
}

/* Display a notice to Purge Cache */
add_action('admin_notices', 'wpsmy_admin_notice');
function wpsmy_admin_notice() {
	global $current_user, $pagenow ;
    $user_id = $current_user->ID;
    $wpsmy_page = $_GET['page'];
    // delete_user_meta($user_id, 'wpsmy_ignore_this');
    /* Check that the user hasn't already clicked to ignore the message */
	// if ( ! get_user_meta($user_id, 'wpsmy_ignore_this') ) {
	if ( $pagenow == 'plugins.php' || $wpsmy_page == 'wp-super-minify' ) {
        echo '<div class="updated"><p>';
        printf('<a style="font-weight: bold; color: #60AA1F;" href="%s">%s</a>', wp_nonce_url( add_query_arg( 'purge_wpsmy', true ), 'purge_wpsmy' ), __( 'Purge WP Super Minify cache', 'wpsmy' ) );
        /* printf('<span style="float: right;"><a href="%1$s">Hide</a></span>', '?wpsmy_notice_ignore=0' ); */
        echo "</p></div>";
	}
}

// add_action('admin_init', 'wpsmy_notice_ignore');
function wpsmy_notice_ignore() {
	global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset($_GET['wpsmy_notice_ignore']) && '0' == $_GET['wpsmy_notice_ignore'] ) {
		// add_user_meta($user_id, 'wpsmy_ignore_this', 'true', true);
		setcookie("wpsmy_hide_admin_notice", 'true');
	}
}

/**
 * Maybe purge wpsmy cache
 */
add_action( 'admin_init', 'purge_wpsmy_cache' );
function purge_wpsmy_cache() {
	if ( ! isset( $_GET['purge_wpsmy'] ) )
		return;
	if ( ! check_admin_referer( 'purge_wpsmy' ) )
		return;
	// Use this as a global cache version number
	update_option( 'wpsmy_cache_ver', time() );
	add_action( 'admin_notices', 'wpsmy_cache_purged_success' );
	// Allow other plugins to know that we purged
	// do_action( 'wpsmy-cache-purged' );

	/* Delete files from /uploads/wp-super-minify directory */
	$wp_upload_dir = wp_upload_dir();
	$wpsmy_files = glob( $wp_upload_dir['basedir'] . '/wp-super-minify/*' );
	if ( $wpsmy_files ) {
		foreach ( $wpsmy_files as $wpsmy_file ) {
			unlink( $wpsmy_file );
		}
	}
}

function wpsmy_cache_purged_success() {
	printf(
		'<div class="updated"><p>%s</p></div>',
		__( 'Success: WP Super Minify cache purged.', 'wpsmy' )
	);
}

// This can used from cron to delete all wpsmy cache files
// add_action( 'wpsmy-cache-purge-delete', 'wpsmy_cache_delete_files' );
function wpsmy_cache_delete_files() {
	$wp_upload_dir = wp_upload_dir();
	$wpsmy_files = glob( $wp_upload_dir['basedir'] . '/wp-super-minify/*' );
	print_r ($wpsmy_files);
	if ( $wpsmy_files ) {
		foreach ( $wpsmy_files as $wpsmy_file ) {
			unlink( $wpsmy_file );
		}
	}
}

/* END OF PLUGIN */
?>

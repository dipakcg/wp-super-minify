<?php
/*
Plugin Name: WP Super Minify
Plugin URI: https://github.com/dipakcg/wp-super-minify
Description: This plugin combine and compress HTML, JavaScript and CSS files to improve page load speed.
Author: Dipak C. Gajjar
Version: 1.0
Author URI: http://www.dipakgajjar.com/
*/

// Important: Don't forget to change version number at line line 7 and 139.
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
	add_menu_page( 'WP Super Minify : Settings', 'WP Super Minify', 'manage_options', 'wp-super-minify', 'wpsmy_admin_options', plugins_url('assets/images/wpsmy-icon-24x24.png', __FILE__) );
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
    <p><input type="submit" value="<?php esc_attr_e('Save Changes') ?>" class="button button-primary" name="submit" /></p>
    </form>
	</td>
	<td style="text-align: left;">
	<div class="wpsmy_admin_dev_sidebar_div">
	<img src="http://www.gravatar.com/avatar/38b380cf488d8f8c4007cf2015dc16ac.jpg" width="100px" height="100px" /> <br />
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-support-this-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3S8BRPLWLNQ38" target="_blank"> Support this plugin and donate </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-rate-this-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://wordpress.org/support/view/plugin-reviews/wp-super-minify" target="_blank"> Rate this plugin on WordPress.org </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-wordpress-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://wordpress.org/support/plugin/wp-super-minify" target="_blank"> Get support on on WordPress.org </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-github-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="https://github.com/dipakcg/wp-super-minify" target="_blank"> Contribute development on GitHub </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-other-plugins-16x16.png' , __FILE__ ) . '" > ';  ?> <a href="http://profiles.wordpress.org/dipakcg#content-plugins" target="_blank"> Get my other plugins </a> </span>
	<span class="wpsmy_admin_dev_sidebar"> <?php echo '<img src="' . plugins_url( 'assets/images/wpsmy-twitter-16x16.png' , __FILE__ ) . '" > ';  ?>Follow me on Twitter: <a href="https://twitter.com/dipakcgajjar" target="_blank">@dipakcgajjar</a> </span>
	<br />
	<span class="wpsmy_admin_dev_sidebar" style="float: right;"> Version: <strong> 1.0 </strong> </span>
	</div>
	</td>
	</tr>
	</table>
	</div>
	<?php
}

// Calling this function will make flush_rules to be called at the end of the PHP execution
function wpsmy_activate_plugin() {

    // Save default options value in the database
    update_option( 'wpsmy_combine_js', 'on' );
    update_option( 'wpsmy_combine_css', 'on' );
}

// On plugin activation, call the function that will make flush_rules to be called at the end of the PHP execution
register_activation_hook( __FILE__, 'wpsmy_activate_plugin' );

class wpsmy_html_compression
{
	// Settings
	protected $info_comment = true;
	protected $remove_comments = true;

	// Variables
	protected $html;
	protected $compress_js;
	protected $compress_css;

	public function __construct($html)
	{
		if (!empty($html)) {
			$this->parseHTML($html);
		}
	}

	public function __toString()
	{
		return $this->html;
	}

	protected function bottomComment($raw, $compressed)
	{
		$raw = strlen($raw);
		$compressed = strlen($compressed);

		$savings = ($raw-$compressed) / $raw * 100;

		$savings = round($savings, 2);

		return '<!--'.PHP_EOL.'*** HTML, JavaScript and CSS of this site is combined and compressed by WP Super Minify plugin v1.0 - http://wordpress.org/plugins/wp-super-minify ***'.PHP_EOL.'*** Total size saved '.$savings.'% from '.$raw.' bytes. Currently '.$compressed.' bytes. ***'.PHP_EOL.'-->';
	}

	protected function minifyHTML($html)
	{
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		$overriding = false;
		$raw_tag = false;
		// Variable reused for output
		$html = '';
		foreach ($matches as $token)
		{
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

			$content = $token[0];

			if (is_null($tag)) {
				if ( !empty($token['script']) ) {
					// Get the option value of Compress JavaScript
					$strip = ( get_option('wpsmy_combine_js', 1) == 'on' ? true : false );
				}
				else if ( !empty($token['style']) ) {
					// Get the option value of Compress CSS
					$strip = ( get_option('wpsmy_combine_css', 1) == 'on' ? true : false );
				}
				else if ($content == '<!--wp-html-compression no compression-->') {
					$overriding = !$overriding;

					// Don't print the comment
					continue;
				}
				else if ($this->remove_comments) {
					if (!$overriding && $raw_tag != 'textarea') {
						// Remove any HTML comments, except MSIE conditional comments
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			}
			else {
				if ($tag == 'pre' || $tag == 'textarea') {
					$raw_tag = $tag;
				}
				else if ($tag == '/pre' || $tag == '/textarea') {
					$raw_tag = false;
				}
				else {
					if ($raw_tag || $overriding) {
						$strip = false;
					}
					else {
						$strip = true;

						// Remove any empty attributes, except:
						// action, alt, content, src
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = str_replace(' />', '/>', $content);
					}
				}
			}

			if ($strip) {
				$content = $this->removeWhiteSpace($content);
			}

			$html .= $content;
		}

		return $html;
	}

	public function parseHTML($html)
	{
		$this->html = $this->minifyHTML($html);

		if ($this->info_comment) {
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		}
	}

	protected function removeWhiteSpace($str)
	{
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n",  '', $str);
		$str = str_replace("\r",  '', $str);

		while (stristr($str, '  '))
		{
			$str = str_replace('  ', ' ', $str);
		}

		return $str;
	}
}

function wpsmy_html_compression_finish($html)
{
	return new wpsmy_html_compression($html);
}

function wpsmy_html_compression_start()
{
	ob_start('wpsmy_html_compression_finish');
}

add_action('get_header', 'wpsmy_html_compression_start');
?>

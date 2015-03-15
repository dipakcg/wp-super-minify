=== WP Super Minify ===
Contributors: dipakcg
Tags: minify, compress, combine, html, css, javascript, js, performance, load, speed, time, yslow, pagespeed, external
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3S8BRPLWLNQ38
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A very light weight plugin, minifies, caches and combine JavaScript and CSS files into a single file on demand to speed up page loads.

== Description ==
This plugin minifies, caches and combine JavaScript and CSS files into a single file on demand to speed up page loads, using [Minify PHP Framework](https://code.google.com/p/minify/) and [minit](https://github.com/kasparsd/minit).

By activating this plugin, you will see the source of your HTML, inline JavaScript and CSS are now compressed and your external Javascript and CSS files are combined into a single file. The size will be smaller and quite helpful to improve your page load speed as well as google page speed and yslow grade (if you care).

To check whether this plugin works properly, simply view your site source or press Ctrl + U from your keyboard. In the end of the source, you should see message something like:

*** Total size saved: 11.341% | Size before compression: 27104 bytes | Size after compression: 24030 bytes. ***

**P.S. It is aways the best policy to open a [support thread](http://wordpress.org/support/plugin/wp-super-minify) first before posting a negative review.**

== Installation ==
1. Upload the `wp-super-minify` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. That's it!

== Frequently Asked Questions ==
= What does this plugin do? =

This plugin minifies, caches and combine JavaScript and CSS files into a single file on demand to speed up page loads. It uses the latest modified time in filename generation to ensure freshness, and loads all external Javascript files asynchronously.

= Any specific requirements for this plugin to work? =

No.

= Is that it? =

Pretty much, yeah.

== Screenshots ==
1. Admin Settings

2. Combined CSS files into a single CSS file (view source)

3. Sample results (pingdom)

== Changelog ==
= 1.3.1, March 15, 2015 =
* Fixed jQuery conflict (by other plugins)

= 1.3, March 12, 2015 =
* Added support for combine external javascript and css files into a single file

= 1.2, Feb 28, 2015 =
* Added News and Updates section in admin options

= 1.1, Jan 03, 2015 =
* Fixed compression related issues
* Replaced manual compression functions with the latest version of [Minify PHP Framework](https://code.google.com/p/minify/)

= 1.0, Oct 04, 2014 =
* Initial release
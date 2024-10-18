=== WP Super Minify ===
Contributors: dipakcg
Tags: minify, compress, combine, html, css, javascript, js, performance, load, speed, time, yslow, pagespeed
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3S8BRPLWLNQ38
Requires at least: 3.5
Tested up to: 6.6
Stable tag: 1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A very light weight plugin, combines, minifies, and caches inline JavaScript and CSS files on demand to speed up page loads.

== Description ==
This plugin combines, minifies, and caches inline JavaScript and CSS files on demand to speed up page loads, using [Minify PHP Framework](https://code.google.com/p/minify/).

By activating this plugin, you will see the source of your HTML, inline JavaScript and CSS are now compressed. The size will be smaller and quite helpful to improve your page load speed as well as google page speed and yslow grade (if you care).

To check whether this plugin works properly, simply view your site source or press Ctrl + U from your keyboard. In the end of the source, you should see message something like:

*** Total size saved: 11.341% | Size before compression: 27104 bytes | Size after compression: 24030 bytes. ***

**Follow the development of this plugin on [GitHub](https://github.com/dipakcg/wp-super-minify)**

**P.S. It is always the best policy to open a [support thread](http://wordpress.org/support/plugin/wp-super-minify) first before posting any negative review.**

== Installation ==
1. Upload the `wp-super-minify` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. That's it!

== Frequently Asked Questions ==
= What does this plugin do? =

This plugin combines, minifies, and caches inline JavaScript and CSS files on demand to speed up page loads.

= Any specific requirements for this plugin to work? =

No.

= Is that it? =

Pretty much, yeah.

== Screenshots ==
1. Admin Settings

== Changelog ==
= 1.6, September 02, 2023 =
* Improve: Security — CSRF Check
* Improve: Data Sanitization / Escaping
* Improve: Removed Promos, News and Updates, and recommendations area completely to make options page more clean

= 1.5.1, March 21, 2017 =
* Improved Promos, News and Updates, and recommendations area.

= 1.5, December 30, 2016 =
* Updated min library to it's latest version

= 1.4, October 1, 2016 =
* Fixed css conflict with WP Performance Score Booster.
* Improved Settings page.
* Added hosting recommendations (referrals).

= 1.3.2, May 24, 2016 =
* Added Settings option (link) under Plugins page
* Moved plugin options (settings) from sidebar to under Settings
* Updated settings page

= 1.3.1, March 17, 2015 =
* Reverted support for combine external javascript and css files into a single file due to conflict with other plugins

= 1.3, March 12, 2015 =
* Added support for combine external javascript and css files into a single file

= 1.2, Feb 28, 2015 =
* Added News and Updates section in admin options

= 1.1, Jan 03, 2015 =
* Fixed compression related issues
* Replaced manual compression functions with the latest version of [Minify PHP Framework](https://code.google.com/p/minify/)

= 1.0, Oct 04, 2014 =
* Initial release

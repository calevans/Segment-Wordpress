=== Segment For WP by in8.io ===
Contributors: juanin8
Donate link: https://in8.io
Tags: segment, tracking, analytics, segmentio
Requires at least: 4.0.1
Tested up to: 5.2
Requires PHP: 5.2.4
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Segment Analytics for WordPress. Event tracking integrated into hundreds of 3rd party tools, see segment.com | Re-written & extended by https://in8.io

== Description ==

The original/official Segment Analytics plugin for WordPress has been deprecated and not updated for a few years. I used the original code as the foundation and added extra features, functionality and fixes.

I will try to write more documentation.

It's all very self-explanatory if you've worked with Segment, but hit me up if you have any questions, suggestions or ideas.

* Built-in support for Ninja Forms and Gravity Forms
* Supports WooCommerce events, you can re-name them and also include some data in the identify call
* Supports client side (JS API) and server-side tracking (HTTP API)
* Ability to filter out roles, custom post types and the admin area
* Rename your events, and easily include userID and email properties in each one
* Ability to include custom user traits in identify calls using meta keys

== Installation ==

1. Upload the plugin zip file through the Plugins section of your site.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enter your Segment API keys into the plugin and choose your events/settings.

== Upgrade Notice ==
 
Update available.

== Screenshots ==

1. Supports client side (JS API) and server-side tracking (HTTP API)
2. Ability to filter out roles, custom post types and the admin area
3. Rename your events, and easily include userID and email properties in each one
4. Built in support for Ninja Forms and Gravity Forms
5. Ability to include custom user traits in identify calls using meta keys
6. Supports WooCommerce events, you can re-name them and also include some data in the identify call

== Frequently Asked Questions ==

1. What do I need? You will need to signup for Segment.com
2. How much does it cost? Plugin is free. Segment is free up to 1,000 users.
3. Will it slow my site down? Depends. The more destinations and the more events you use, the slower things can go. The same way as if you installed the scripts directly.

== Changelog ==

= 1.0.9 =
* More updates to bring woocommerce integration inline with their 'new' functions vs legacy ones I used to begin with

= 1.0.8 =
* Moving to new WooCommerce methods to get order data in order to avoid some error notices

= 1.0.7 =
* Small Fixes

= 1.0.6 =
* Fixed an bug with WC functions


= 1.0.5 =
* Removed some unused functions, fixed a potential bug when reading 'signed up' cookies.

= 1.0.1 =
* Updated README and made the plugin description more helpful

= 1.0 =
* First version
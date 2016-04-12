=== OM4 Service ===
Tags: OM4, cache, revisions, service
Requires at least: 4.0
Tested up to: 4.5
Stable tag: 1.2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OM4 Service / Web Assist integration. Also improves default WordPress functionality.

== Description ==

* Adds the OM4 Service orange button to the WordPress dashboard.
* Automatically flushes caches (WP Engine or W3TC) whenever:
	* Custom CSS rules are saved.
	* Header rules are saved.
	* Members Only settings/options are saved.
* Automatically overrides WordPress' revisions limit to 5 per post/page (even on WP Engine sites where revisions are disabled by default)
* Automatically exclude all /admin/ pages (and sub-pages) from WordPress search results.
* Ensure Gravatars/Avatars don't have an empty alt tag.
* Remove the rel=prev and rel=next links from the <head> section.

== Installation ==

1. Activate the plugin.

== Changelog ==

= 1.2.6 =
* WordPress 4.4 compatibility.
* Revise OM4 Service links.

= 1.2.5 =
* WordPress 4.1 compatibility.
* Readme updates.

= 1.2.4 =
* Detect website guide pages if using .html page extensions.
* Revise OM4 Service links.

= 1.2.3 =
* If the Imsanity plugin is active, set the default settings to 2560x2560 (instead of 1280x1280).

= 1.2.2 =
* Remove the rel=prev and rel=next links from the <head> section.

= 1.2.1 =
* Ensure Gravatars/Avatars don't have an empty alt tag.
* Wrap nav menus in <span> tags.

= 1.2 =
* Code improvements.
* Caches module.
* Revisions module.
* Search module.
* Add readme file.

= 1.1.2 =
* 'Ask a Question' now goes to the submit ticket screen on my.om4.com.au.

= 1.1.1 =
* Link to clientarea.php instead so the client must log in before submitting a ticket.

= 1.1.0 =
* Code improvements.
* Link to my.om4.com.au.

= 1.0.0 =
* Initial release.
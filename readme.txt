=== OM4 Service ===
Tags: OM4, cache, revisions, service, rest api, search
Requires at least: 4.4
Tested up to: 5.2.2
Stable tag: 1.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

OM4 Service Desk integration. Also improves default WordPress functionality.

== Description ==

* Adds the OM4 Service orange button to the WordPress dashboard.
* Automatically flushes caches (WP Engine, Beaver Builder, WP Rocket) whenever:
	* Custom CSS rules are saved.
	* Header rules are saved.
	* Members Only settings/options are saved.
* Automatically overrides WordPress' revisions limit to 5 per post/page (even on WP Engine sites where revisions are disabled by default)
* Automatically exclude all /admin/ pages (and sub-pages) from WordPress search results and unauthenticated REST API requests.
* Only show image media library items in unauthenticated REST API requests.
* Disable users endpoints for unauthenticated REST API requests.
* Ensure Gravatars/Avatars don't have an empty alt tag.
* Remove the rel=prev and rel=next links from the <head> section.
* If the Imsanity plugin is active, set the default settings to 2560x2560 (instead of 1280x1280).
* Automatically wrap WordPress 3+ nav menu items in a <span> tag.
* Alert if an incompatible plugin is active in WP Engine staging / development environments.

== Installation ==

1. Activate the plugin.

== Changelog ==

= 1.6.2 =
* Add Linksync to the list of plugins that are incompatible with WP Engine staging / development environments.

= 1.6.1 =
* Remove OM4 Services link from OM4 Service menu.

= 1.6 =
* Use relative URLs in Beaver Builder’s dynamically generated CSS rules so that background images also load via a CDN.
* Whenever OM4 Custom CSS rules are saved, also flush the Beaver Builder and WP Rocket caches.

= 1.5 =
* Alert if incompatible plugin active in WP Engine staging / development environment.
* Remove comments /feed/ references as well as category/archive /feed/ references.
* Update Knowledge Base link.

= 1.4 =
* Unauthenticated WordPress REST API requests: disable users endpoints.

= 1.3 =
* WordPress 4.7 compatibility
* Unauthenticated WordPress REST API requests: exclude admin pages and non image media items.

= 1.2.7 =
* Avoid PHP notice for comments with no author.
* Avoid PHP notice for comments where the author is just an email address.
* Mark as compatible with WordPress 4.5.x.

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

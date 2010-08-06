=== Plugin Name ===
Contributors: Hudson Atwell / Sonar Bangla
Donate link: mailto:Hudson.Atwell@gmail.com
Tags: Google Indexing, SEO, Sitemaps, RSS, index, google
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 2.0.4

Check if google indexed your pages/posts. Must have <a href='http://wordpress.org/extend/plugins/google-sitemap-generator/' target=_blank>XML Sitemap Generator Plugin</a> to work with this plugin.

== Description ==
IndexSpy creates a table of all your blog's posts and pages, letting you know what content has been indexed on Google and what content hasn't. IndexSpy also provides tools for creating RSS feeds out of un-indexed items as well as exporting selected items as a copy/pastable list (for your indexing campaigns). Furthermore you can select and delete old un-indexed posts on the fly so as to focus ranking power into your indexed pages.This plugin was originally developed for my users @ <a href="http://www.blogsense-wp.com">BlogSenseWP - Advanced Automation and Content Management</a>
== Installation ==1. Upload `wp-indexspy` folder to the `/wp-content/plugins/` directory2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==


== Screenshots ==
1. Admin Panel

== Changelog ==

= 1.5 =
* Released first version.

= 1.5.2 =
* Renamed plugin name from wp-indexspy to indexspy, and fixed errors resulting from namechange.

= 1.5.3 =
* Fixed path to broken image.

= 1.5.4 =
* Added variable CURL or file_get_contents() methods of grabbing content (with autodetect) from the xml sitemap. Some servers will not permit one or the other.

= 1.5.5 =
* Added note to users that CURL must be enabled for your server to use plugin.

= 1.5.6 =
* changed way sitemape is passed as variable (used urlencode and urldecode)

= 1.5.7 =
* fixed bug that was placed in during 1.5.5= 2.0.1 =* Eliminated need for XML Sitemap Generator. * Added ability to move selected posts to trash. * Added ability to create list of selected post.* Fixed issue with session variables that caused results of other blogs to display in other boogs if index spy was ran within the same browser session.= 2.0.2 =* Fixed RSS feed building.= 2.0.3 =* Removed error messages if XML Sitemap Generator not found= 2.0.5 =* Fixed Issue with trashing multiple items
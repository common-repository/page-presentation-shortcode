=== Page Presentation Shortcode ===
Contributors: alineasupport
Donate link: 
Tags: slide show, presentation, page redirect, re direct, redirect, post redirect, shortcode, alineainteractive
Requires at least: 2.7
Tested up to: 4.4.2
Stable tag: 1.1.0

Create a rolling presentation using the pages and posts on your site.

== Description ==
This shortcode enables you to create a rolling page/post presentation sequence by redirecting 
the current post/page after waiting for a choosen number of seconds.

If the destination page is not in a published state the shortcode processes each destination page's shortcode until it find one that is.

[pagepresenter id='101' after='20']
[pagepresenter slug='my_page_slug' after='20']
[pagepresenter url='http://www.domain.com/page-1' after='20']
[pagepresenter url='/page-1' after='20']

If the destination post/page doesn't live in the same Wordpress installation then no redirection will occur.

Based on the excellent Shortcode Redirect: https://wordpress.org/support/view/plugin-reviews/shortcode-redirect 
= Donate =

= Features =
* Works on pages and posts
* NO settings or configurations to deal with
* Define a URL, post/page ID or slug to redirect the user to
* Define how many seconds to wait.
* Disable presentation page/post slides by setting the status to 'Daft' without interrupting the whole presentation.

== Installation ==
1. Upload 'wp_page_presenter.zip' to the '/wp-content/plugins/' directory.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Add the shortcode on the pages/posts that you want to redirect. 


== Note ==
Test with version 4.5.2 but should work with older versions of WordPress.

== Frequently Asked Questions ==

== Upgrade Notice ==
n/a

== Changelog ==
= 1.1.00 =
* Initial release

== Screenshots ==
none
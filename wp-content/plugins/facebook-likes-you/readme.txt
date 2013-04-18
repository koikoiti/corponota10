=== Facebook Likes You! ===
Contributors: sproject
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sochalewski%40gmail%2ecom&lc=PL&item_name=Facebook%20Likes%20You%21&item_number=fly%2ddonate&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: facebook, like, button, share, social, facebook like button, facebook like, facebook like box, like box, facebook send button, send button, google +1, google +1 button, widget
Requires at least: 2.9
Tested up to: 3.3.2
Stable tag: 1.5.4

Facebook Likes You! is simple plugin which makes it easy to add Like Button and widgetable Like Box. Google +1 isn't a problem too!

== Description ==

Facebook Likes You! is simple plugin which makes it easy to add Facebook Like Button and widgetable Facebook Like Box. Google +1 Button isn't a problem too! It's fully configurable, so you can decide where to append the button. Currently in the following languages:<br />

* English<br />
* Belarusian (беларуская мова)<br />
* Indonesian (Bahasa Indonesia)<br />
* Italian (Italiano)<br />
* Polish (język polski)<br />
* Spanish (Español, castellano)<br />
* Turkish (Türkçe)<br />
* incomplete in Brazilian Portuguese, Czech, German, and Russian

If you have created your own language pack, or have an update of an existing one, you can send [gettext .po and .mo files](http://codex.wordpress.org/Translating_WordPress) to me (you can find my e-mail address [here](http://wolnaelekcja.pl/o-mnie/)) so that I can bundle it into Facebook Likes You!

== Installation ==

1. Upload folder named `facebook-likes-you` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= I don't see the button. It doesn't work! =
Try to uncheck 'Use XFBML' checkbox or/and register your own app ID. If it doesn't appear only in Internet Explorer, remove from your theme root `<html>` element and put `<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">`.

= How to add Like button in another place than available in settings? =
You can use simple BBCode `[fb-like-button]` and place it by WordPress editor or – in another space (i.e. in your WordPress theme) – put `<?php echo do_shortcode('[fb-like-button]'); ?>`.

= Facebook Likes You! crashed my Open Graph! =
Simply uncheck 'Use Open Graph' in settings. Extremely recommended if you've got another Open Graph plugin or put these meta tags on your own.

== Screenshots ==

1. Plugin options
2. Facebook Likes You! is multilingual

== Changelog ==

= 1.5.4 =
* Fixed settings page

= 1.5.3 =
* Fixed auto language broken in 1.5.2

= 1.5.2 =
* Updated Facebook implementation
* Added Belarusian translation thanks to [Alexander Ovsov](http://webhostinggeeks.com/science/)

= 1.5.1 =
* Fixed critical bug: really important if use Open Graph and have no app ID

= 1.5 =
* Fix for W3C Valid XFBML
* Removed HTML5 Mode
* Fix for relative thumbnails' URL in Custom Fields
* Rewrited Facebook Like Button and Box code generator

= 1.4.7 =
* Support for relative thumbnails' URL in Custom Fields

= 1.4.6 =
* Supported Custom Fields with thumbnails ('thumb' and 'thumbnail') for Open Graph

= 1.4.5 =
* Fixes for Open Graph

= 1.4.4 =
* Fix for Open Graph (extremely important if you use Open Graph and don't have own app ID)

= 1.4.3 =
* One more fix for Open Graph
* Fixed settings

= 1.4.2 =
* Fixed critical bug (really important update!)

= 1.4.1 =
* Few fixes for Open Graph

= 1.4 =
* Open Graph support (great if you asked me about issue with wrong thumbnails)

= 1.3.3 =
* Margins work for Google +1 Button too

= 1.3.2 =
* Fixed Google +1 when showed up on Home (Search, and Archive possibly too) thanks to Thomas Meyer
* Added Spanish translation thanks to Jordi Sancho

= 1.3.1 =
* Simplified settings thanks to exclusion dependencies
* A little updated generated code

= 1.3 =
* Added Google +1 Button
* Fixed height of Like Button and Like Box (both iframe only)
* A little optimization
* Added Turkish translation thanks to Semih Yeşilyurt
* Minor changes

= 1.2.1 =
* Updated Send button

= 1.2 =
* Added Send button

= 1.1.7 =
* Better Like Box for non-XFBML

= 1.1.6 =
* Simplified, but still powerful settings
* Updated all translation files

= 1.1.5 =
* Better validation for XFBML (like in 1.1.2 and earlier)
* Added German translation thanks to Stefan Meier

= 1.1.4 =
* Simplified margin settings

= 1.1.3a =
* Quick fix for XFBML

= 1.1.2 =
* Added important information about PHP to place the button manually

= 1.1.1 =
* Minor changes

= 1.1 =
* New one layout style "box_count"
* Back to HTML4/XHTML (but you can switch to HTML5)

= 1.0.1 =
* Fixed ability to show important info for users in plugin options

= 1.0 =
* PLUGIN RENAMED TO 'FACEBOOK LIKES YOU!'
* Facebook Like Box as fully configurable widget (requires WP &#8805; 2.8)
* Better validation XFBML version and perfect validation iframe (both as HTML5)
* License updated to GPLv3
* Minor changes
* Added Russian translation thanks to Denis Kuligin
* Added Indonesian translation thanks to Dadan Adrian Y.

= 0.5.3 =
* Added Czech translation thanks to Georgij Gadjukin
* Added Italian translation thanks to Paolo Nicorelli

= 0.5.2 =
* Simplified customization of margins in XFBML version
* Minor changes in translation files

= 0.5.1 =
* Important fix for non-XFBML version

= 0.5 =
* Possibility to place the button almost anywhere you want by simple code
* Huge optimization
* Minor changes

= 0.4.4 =
* Fixed an issue when WPLANG is not defined

= 0.4.3 =
* Fixed like button on home page in XFBML version

= 0.4.2 =
* Minor changes. Major soon!

= 0.4.1 =
* More stable release thanks to improved SDK
* Added Brazilian Portuguese translation thanks to Paulo Vital

= 0.4 =
* Fixed bug with iframe version

= 0.3.1 =
* Possibility to exclude page too
* Fixed readme for wp.org standards
* Minor fixes

= 0.3 =
* Possibility to exclude category/post by ID, name or slug
* Fixed polish translation
* Minor changes

= 0.2.1 =
* Possibility to use XFBML version without an app ID
* Minor changes

= 0.2 =
* Added XFBML support
* Height is automatically changed accordingly to faces
* Minor fixes

= 0.1 =
* First stable release
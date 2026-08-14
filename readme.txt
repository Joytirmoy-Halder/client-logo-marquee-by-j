=== Client Logo Marquee by J ===
Contributors: joytirmoyhalder
Tags: elementor, logo, marquee, clients, carousel
Requires at least: 5.9
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A seamless, GPU-accelerated client logo marquee widget for Elementor. No jQuery, no carousel library, no per-frame JavaScript.

== Description ==

An infinite logo strip for Elementor that scrolls without a seam and without costing you frames.

The loop is one composited CSS transform. The JavaScript only measures the strip, clones the logo set enough times to cover the screen, and pauses the animation while the strip is off-screen.

Features:

* Seamless infinite loop, in either direction
* Bare logos or logos in cards, with hover lift and an optional sheen sweep
* Greyscale to full colour reveal on hover, every value adjustable
* Soft edge fades, so logos dissolve instead of being clipped
* Pause on hover and on keyboard focus
* Pauses itself when scrolled out of view
* Responsive speed, spacing, logo height and fade width per breakpoint
* Optional link, new tab and nofollow per logo
* Built-in image resolution guide in the panel
* Lazy loaded, srcset aware images with no layout shift
* Static centred grid fallback for prefers-reduced-motion
* CSS and JS load only on pages that use the widget
* No jQuery dependency

== Installation ==

1. Upload the plugin folder to /wp-content/plugins/, or install the zip through Plugins > Add New > Upload Plugin.
2. Activate the plugin.
3. Edit a page with Elementor and drag in Client Logo Marquee, under the By J category.

== Frequently Asked Questions ==

= What size should my logos be? =

Export at twice the logo height you set in the panel, on a transparent background. For the default 44px height that means 88px tall, for example 320 x 88. SVG is ideal for wordmarks. Trim any empty padding from the file before uploading, as that is the usual cause of a strip that looks uneven.

= Do I need Elementor Pro? =

No. Elementor free is enough.

= Why is there a gap in the strip? =

That happens when the logo set is narrower than the screen and JavaScript has not run. Check the browser console for errors from another plugin.

= Does it work with only two or three logos? =

Yes. The widget clones the set as many times as it needs to fill the screen.

== Changelog ==

= 1.0.3 =
* Logo sizing is now enforced against themes that stretch images with img { width: 100% }, which could blow logos up to full width.
* The stylesheet and script are now loaded inside the Elementor editor preview, so a freshly dropped widget is styled without reloading the editor.
* Default Image Resolution is now Full, so logos are never handed to a cropped WordPress size by accident.

= 1.0.2 =
* The Custom option is now available in Image Resolution, resolved through Elementor's own image size handler. Set the Height only and leave the Width empty to keep each logo's aspect ratio.

= 1.0.1 =
* Fixed a fatal error on sites running certain Elementor versions, caused by overriding has_widget_inner_wrapper(). The override has been removed.

= 1.0.0 =
* First release.

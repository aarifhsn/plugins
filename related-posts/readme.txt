=== AFS Related Posts ===
Contributors: Arifhassan
Tags: related posts, carousel, owl carousel, posts, author bio, related content
Requires at least: 5.0
Tested up to: 6.6.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Displays related posts at the end of your content using Owl Carousel. Includes post thumbnail, post title,  author bio, post thumbnails, and excerpts.

== Description ==

AFS Related Posts is a WordPress plugin that automatically displays related posts at the end of your content. The related posts are displayed in a stylish and responsive Owl Carousel, enhancing the user experience and keeping visitors engaged on your site. 

**Features:**

- Displays related posts based on categories.
- Shows post thumbnails, excerpts, and author bio.
- Fully responsive carousel powered by Owl Carousel.
- Easy to customize with CSS.

**How It Works:**

1. The plugin automatically detects the categories of the current post and fetches related posts from the same categories.
2. The related posts are displayed in a beautiful carousel format, which is fully responsive and customizable.

== Installation ==

1. After unzip, upload the `related-posts` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. No additional configuration is required. The related posts carousel will automatically appear at the end of your posts.

== Frequently Asked Questions ==

= How do I customize the appearance of the related posts? =

You can customize the appearance of the related posts by modifying the `afs-related-posts.css` file located in the `assets/css/` directory. Additionally, you can override the styles in your theme's CSS file.

= How do I control the number of related posts displayed? =

Currently, the plugin displays 3 related posts by default. You can modify this by editing the `$args` array in the `display_related_posts()` method located in the `class-afs-related-posts.php` file.

= Can I change the carousel settings? =

Yes, the Owl Carousel settings can be adjusted by editing the `afs-owl-init.js` file in the `assets/js/` directory.

== Screenshots ==

1. Example of related posts displayed in a carousel with post thumbnails, excerpts, and author bio.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release of the AFS Related Posts plugin. No upgrade available.

== Credits ==

This plugin uses the following third-party resources:

- [Owl Carousel](https://owlcarousel2.github.io/OwlCarousel2/) under the MIT license.

== License ==

This plugin is licensed under the GPLv2 or later license. For more details, see the [License URI](https://www.gnu.org/licenses/gpl-2.0.html).

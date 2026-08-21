=== Video Popup for Elementor ===
Contributors: zelvigo
Tags: video popup, youtube lightbox, vimeo lightbox, gutenberg block, video lightbox, elementor
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.8.0
License: GPLv3
License URI: https://gnu.org

Create beautiful video lightbox popups with Gutenberg, shortcode, or Elementor. Supports YouTube and Vimeo videos using lightweight Magnific Popup.

== Description ==

### WordPress Video Lightbox Plugin

Video Popup is a lightweight plugin for creating YouTube and Vimeo lightbox popups on any WordPress site. Use the **Gutenberg block**, **shortcode**, or **Elementor widget** — Elementor is optional.

Built with Magnific Popup for a fast, responsive lightbox experience with a smooth fade effect.

### WordPress Developer Studio
* [Zelvigo Dev Studio](https://zelvigo.com/)

### Features

* **Gutenberg block** — add video popups from the block editor (Zelvigo block category)
* **Shortcode** — `[wptd_video_popup]` works on any page or post
* **Elementor widget** — optional, loads only when Elementor is active
* **Trigger types** — text, icon, or image
* **YouTube & Vimeo** — including `youtu.be` short links
* **Popup width** — customize max video width in pixels
* **Overlay color & opacity** — theme color palette plus opacity slider (0–100%)
* **Autoplay** — play video when the popup opens (enabled by default)
* **Responsive & retina ready** — works on all screen sizes

### Gutenberg Block Settings

* Video URL (YouTube or Vimeo)
* Autoplay video (on/off)
* Trigger type: text, icon, or image
* Popup width (px)
* Overlay background color (theme palette)
* Overlay opacity (%)

### Shortcode Example

`[wptd_video_popup url="https://www.youtube.com/watch?v=VIDEO_ID" trigger="text" text="Watch Video" width="900" bg_color="rgba(0,0,0,0.5)" autoplay="1"]`

**Parameters:** `url`, `trigger` (text|icon|img), `text`, `icon`, `img`, `width`, `bg_color`, `autoplay`, `extra_class`

== Installation ==

1. Upload the `wptd-video-popup` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Add a **Video Popup** block in the editor, use the shortcode, or add the Elementor widget.

== Frequently Asked Questions ==

= Can I use the plugin without Elementor? =

Yes. The Gutenberg block and shortcode work without Elementor. The Elementor widget is an optional enhancement when Elementor is installed.

= Does it work with any theme? =

Yes. It works with any WordPress theme that supports the block editor or shortcodes.

= Does autoplay work on mobile? =

Autoplay runs when the visitor clicks the trigger and the popup opens. Some mobile browsers may restrict autoplay with sound; the user may need to tap play inside the video player.

= Can I extend the plugin? =

Yes. Contact us at [Zelvigo](https://zelvigo.com/) for custom features or integrations.

== Screenshots ==

1. Video Popup Gutenberg block in the block editor.
2. Video Popup Elementor widget settings.
3. Video Popup shortcode documentation in admin.
4. Text, icon, and image trigger examples.
5. Frontend lightbox popup with YouTube video Sample 1
6. Frontend lightbox popup with YouTube video Sample 2
7. Frontend lightbox popup with YouTube video Sample 3

== Changelog ==

= 1.8.0 =
* Added native **Gutenberg block** (`wptd/video-popup`) under the Zelvigo block category
* Added shared renderer architecture for shortcode and block output
* **Shortcode and Gutenberg block now work without Elementor** — Elementor widget remains optional
* Removed admin notice requiring Elementor to be installed
* Gutenberg block settings: video URL, trigger type (text/icon/image), popup width, overlay color, overlay opacity, autoplay
* Added **overlay opacity slider** (0–100%) with theme color palette for overlay background
* Added **autoplay** option — plays video when popup opens (enabled by default)
* Added **youtu.be** short URL support for YouTube videos
* Improved block editor preview — shows trigger only (no broken iframe preview)
* Refactored frontend JavaScript for per-popup autoplay and embed URL handling
* Added `package.json` and block source files for optional `@wordpress/scripts` build
* Updated plugin description, FAQ, and compatibility (WordPress 6.0+, PHP 7.4+)

= 1.6.0 =
* Rebranded plugin under Zelvigo
* Updated WordPress and PHP compatibility
* Updated documentation and support links
* General maintenance and security improvements

= 1.5.1 =
* Fixed $cur_class issue

= 1.5 =
* Added allowfullscreen property for video iframe

= 1.4 =
* Updated elementor _register_controls deprecation issue

= 1.3 =
* Updated js code for mobile popup issue

= 1.2.1 =
* Updated js code for dynamic loaded content

= 1.2 =
* Removed some unwanted files and reduced styles.

= 1.1 =
* Video Popup elementor shortcode added.
* Video frame style updated
* POT (translation strings) file updated.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.8.0 =
Major update: Gutenberg block support, no Elementor required for shortcode/block, autoplay, overlay opacity, and youtu.be URL support.

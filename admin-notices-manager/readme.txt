=== Admin Notices Manager ===
Contributors: Melapress
Plugin URI: https://melapress.com/wordpress-admin-notices/
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.html
Tags: admin notices, hide admin notices, manage admin notices, notices, dashboard notices
Requires at least: 5.0
Tested up to: 7.1
Stable tag: 1.6.1
Requires PHP: 7.2.0

Better manage admin notices & never miss important developer messages!

== Description ==

<strong>Better manage admin notices & never miss an important message!</strong><br />

WordPress core, themes and plugins developers use admin notices to send you important messages about your website and their software that you use. However, more often than not a cry wolf situation is created due to the overwhelming number of notices and the nature of these messages.

Use the Admin Notices Manager plugin to better manage your WordPress website admin notices - read them at your own convenience and not miss any important WordPress core and developer messages.

##The problem

Pretty much anyone who uses WordPress knows about admin notices. Unfortunately though, they have a negative connotation of them. Very often users are overwhelmed by the number of admin notices that pop up on their WordPress dashboard. Sometimes there are so many that the WordPress dashboard is below the scroll.

This has resulted in users ignoring the admin notices and not reading important messages and information from WordPress core, plugins and also themes developers.

##The Solution

The Admin Notices Manager plugin solves this problem by moving the admin notices out of the main dashboard view to a central place, so you are not disrupted. 

This allows you to keep on working and read the notices at your convenience at a later time, without missing any important WordPress core, plugins and themes messages.

##How it works

The Admin Notices Manager plugin is very easy to use; simply install and activate it on your WordPress website and it will automatically capture all the notifications.

The plugin solves this problem by moving the admin notices out of the main dashboard view to a central place, so you are not disrupted. 

The plugin notifies you of new notices by highlighting the number of new notices in the WordPress toolbar next to your username.

##Benefits & features

* Easily customize how & where the admin notices are displayed
* Customize what action should be taken for every different admin notices type
* Capture any type of admin notices, including ones with custom types
* Easily manage admin notices for a clutter-free admin area
* WordPress system admin notices are shown in the WordPress dashboard


##FREE Plugin Support
Support for Admin Notices Manager is available for free via:

* [forums](https://wordpress.org/support/plugin/admin-notices-manager/)

* [email](https://melapress.com/support/)

##Maintained & Supported by Melapress

Melapress builds high-quality niche WordPress security & management plugins. It's suite of plugins consists of:

* [WP 2FA](https://melapress.com/wordpress-2fa/)
* [Melapress Role Editor](https://melapress.com/wordpress-user-roles-editor/)
* [Melapress Login Security](https://melapress.com/wordpress-login-security/)
* [WP Activity Log](https://melapress.com/wordpress-activity-log/)

Visit the [Melapress website](https://melapress.com/) for more information about the company and the plugins it develops.

##Related Links and Documentation

* [What are WordPress admin notices & how do they work?](hhttps://melapress.com/how-wordpress-admin-notices-work/)
* [Why WordPress admin notices matter & how to manage them effectively](https://melapress.com/manage-wordpress-admin-notices-effectively/)
* [Admin Notices Manager plugin page](https://melapress.com/wordpress-admin-notices/)

== Installation ==

=== From within WordPress ===

1. Visit 'Plugins > Add New'
1. Search for 'Admin Notices Manager'
1. Install & activate the plugin from your Plugins page.

=== Manually ===

1. Download the plugin from the [WordPress plugins repository](https://wordpress.org/plugins/admin-notices-manager/)
1. Unzip the zip file and upload the `admin-notices-manager` folder to the `/wp-content/plugins/` directory
1. Activate the Admin Notices Manager plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The plugin is very easy to use. Once installed it will automatically capture all notifications.
2. When there are new admin notices, the plugin will highlight it by showing the number of notifications in the WordPress toolbar.
3. Admin notices are shown in a retractable sidebar, from where you can mark them as read or permanently hide them.
4. Fully configurable plugin: configure how the plugin should handle the different types of admin notices.
5. Optionally, admin notices can also be shown in a pop-up window, from where you can mark them as read or permanently hide them.

== Changelog ==

= 1.6.1 (2026-09-17) =

* **Security patch**

  * Administrator SQL Injection reported by Ananda Dhakal.

= 1.6.0 (2025-02-24) =

* **New functionality**

  * Added an option to allow specific notifications to appear in the dashboard as usual.

* **Improvements**

  * Improved the handling of translations, ensuring that strings within JavaScript code are translated correctly.
  * Improved how notifications are hidden, significantly reducing the visual flash that could occur when a notification disappeared.

* **Bug fixes**

  * Fixed an issue where site notifications were not hidden correctly on Simple History plugin pages.
  * Resolved a conflict with the Admin Site Enhancements plugin that could cause its main page to appear blank.
  * Fixed a compatibility issue with Gravity Forms that could cause some Gravity Forms pages to appear blank.
  * Fixed an issue where the plugin incorrectly hid the H5P plugin consent notice.
  * Fixed an issue where some notices were missing the Hide Forever button.
  * Fixed the Reset List of Hidden Notices functionality.
  * Fixed an issue where success notices were not excluded from being hidden according to the plugin settings.
  * Improved the counting of hidden notifications.

= 1.5.0 (2024-06-05) =

* **Improvements**

  * Updated URLs and links to point to the Melapress website.
  * Updated the license file.
  * Added additional sanitization and improved the general code structure.
  * Added a Settings link to the plugin listing on the Plugins page.

* **Bug fixes**

  * Fixed an issue where WordPress system notifications were captured as third-party notifications and hidden when they should have remained visible.
  * Fixed a missing authorization vulnerability that could allow authenticated subscribers to retrieve a list of user email addresses.

= 1.4.0 (2023-02-15) =

* **New features and functionality**

  * Hidden notices can now be purged from the plugin settings.

* **Improvements**

  * Added various UI improvements.
  * Improved support for third-party plugins.

* **Bug fixes**

  * Fixed a conflict with Loco Translate caused by the use of `notice` CSS classes.

= 1.3.0 (2022-03-08) =

Release notes: [Admin Notices Manager 1.3: Better support for custom admin notices](https://www.wpwhitesecurity.com/anm-1-3-0/)

* **New features and functionality**

  * Added the ability to capture and manage custom admin notices using a specific CSS selector.
  * Added the ability to specify which users should have admin notices hidden.

* **Improvements**

  * Improved PHP 8 compatibility.
  * Applied the WordPress Coding Standards throughout the plugin.
  * Standardized the translation domain used in the plugin.
  * Improved the process that deletes plugin data during uninstall.

* **Bug fixes**

  * Fixed a conflict with WP Mail SMTP that could break the plugin settings pages.
  * Fixed a fatal error that could occur with specific themes.

= 1.2.0 (2021-08-10) =

Release notes: [Admin Notices Manager 1.2: More information about admin notices and other improvements](https://www.wpwhitesecurity.com/anm-1-2-0/)

* **New features**

  * Added the option to permanently hide specific admin notices.
  * Added the date and time when an admin notice was detected.
  * Added categorization by admin notice level.

* **Improvements**

  * Admin notices are now displayed in a sidebar instead of a pop-up window.
  * Plugin data is completely removed from the database when the plugin is uninstalled.
  * Improved admin notice readability.
  * Removed duplicate code.

* **Bug fixes**

  * Fixed an issue where the plugin settings needed to be saved before the plugin could capture admin notices.

= 1.1.0 (2021-01-11) =

Release notes: [Admin Notices Manager 1.1: Choose which admin notices you want to see](https://www.wpwhitesecurity.com/anm-1-1-0/)

* **New features**

  * Added settings to configure which types of admin notices should be ignored, captured and displayed in the central list, or hidden completely.

* **Improvements**

  * The plugin automatically detects and displays WordPress system messages, such as the User Profile Updated notice. The plugin can also be configured to capture these messages.

* **Known issue**

  * Tabs in the WooCommerce Memberships interface may disappear because of a plugin conflict.

= 1.0 =

Release notes: [Admin Notices Manager: Announcing the new plugin](https://www.wpwhitesecurity.com/admin-notices-manager-announcing-the-new-plugin/)

* First release.

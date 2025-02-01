=== Shinobi Security Notifier ===
Contributors: thewizershinobi
Donate link: https://wpshinobi.com
Tags: security, monitoring, updates, wordpress repository, notifications
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Short Description ==
Shinobi Security Notifier provides real-time alerts when installed extensions are removed from the WordPress repository, helping administrators maintain site security.

== Description ==
Shinobi Security Notifier actively monitors the WordPress Extension Repository and alerts administrators when any installed extension has been closed or removed. This ensures timely awareness of potential security risks.

**Features:**
* Daily automatic check for removed extensions.
* Admin dashboard notifications for closed/removed extensions.
* Email notifications to site administrators.
* Manual status check via settings page.
* Lightweight and follows WordPress API standards.

== Installation ==
1. Download the extension ZIP file.
2. Navigate to `Extensions > Add New` in your WordPress dashboard.
3. Click **Upload Extension** and select the ZIP file.
4. Click **Install Now** and then **Activate**.

== Frequently Asked Questions ==
= How does this extension work? =
It checks your installed extensions against the official WordPress Extension Repository and alerts you if any have been removed or closed.

= How often does the check run? =
The check runs automatically once daily via WordPress Cron. You can also trigger a manual check from the settings page.

= What happens if an extension is removed? =
You'll receive a notification in the WordPress admin dashboard and an email alert.

= Does this extension affect performance? =
No, it runs lightweight API calls and only checks when needed.

== Changelog ==
= 1.0.0 =
* Initial release with daily extension checks, admin notifications, and email alerts.

== Upgrade Notice ==
= 1.0.0 =
Initial release. No upgrades needed.

== Support ==
For help and support, visit [wpshinobi.com](https://wpshinobi.com).

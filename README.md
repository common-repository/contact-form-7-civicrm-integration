=== Plugin Name ===
Contributors: jaapjansma,aydun,alexreiner
Donate link: https://civicoop.org/
Tags: contact form 7, cf7, civicrm
Requires at least: 4.3
Tested up to: 5.5.1
Requires PHP: 7.2
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Contact Form 7 CiviCRM integration.

== Description ==

This plugin adds integration for CiviCRM to Contact Form 7. With this plugin it is possible to submit a contact to a local or remote CiviCRM system.

This adds a **CiviCRM** tab to Contact Form confgurations.  If you enable CiviCRM processing the form will be submitted to the CiviCRM API v3.  For information about using CiviCRM's API see https://docs.civicrm.org/dev/en/latest/api/ 

The API requires an **Entity** and an **Action**.  You may need to add additional parameters depending for the Entity/Action you specify.  Use the API Explorer on your CiviCRM system to determine what additional parameters are required.  The form parameters will automatically be included in the API call and the form variables need to match those expected by the API.
For example, if the API call needs a field called *first_name* then your form template should have a field called *first_name*  (not *first-name* or *firstName* etc)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the **Settings > CiviCRM Contact Form 7 Settings** screen to configure the plugin, or follow the **Settings** link from the plugins page
1. Enable CiviCRM on each CF7 form.


== Screenshots ==

1. This screenshot shows the settings screen
2. This screenshot shows the screen for enabling and setting up CiviCRM integration on a contact form.

== Changelog ==

= 1.8 =
Fix for validation when no local CiviCRM
= 1.7 =
Added validation to settings page
Added link to settings from plugins page
= 1.6 =
* Updated readme
= 1.5 =
* Updated readme
= 1.4 =
* Updated text domain
= 1.3 =
* Updated data handling and added a path for the civicrm installation
= 1.2 =
* Updated readme
= 1.1 =
* Changed text domain
= 1.0 =
* Initial commit



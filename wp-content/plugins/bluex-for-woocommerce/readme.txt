=== Plugin BlueX for WooCommerce ===
Contributors: @soporteblue
Tags: blue express rates, live rates, shipping rates, blue express, woocommerce shipping
Requires at least: 4.5
Tested up to: 6.4.1
Stable tag: 2.1.4
Requires PHP: 7.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Once the plugin is installed, you need to go to the integration section in the woocommerce settings and add the data delivered by blue express. Also, 
create a rest api user, this must be in the advanced options and deliver it to Blue Express for the correct functioning of the module.

== Screenshots ==

1. Blue Express general settings
2. Blue Express custom services' settings
3. Blue Express shipping methods in the cart

== Changelog ==

= 1.0.0 - 2022-11-03 =
* First release!
= 1.0.1 - 2022-12-15 =
* Changes requested 
= 1.0.2 - 2023-03-02 =
* Update of the tariff service for dispatches
= 1.0.3 - 2023-03-02 =
* Fix get pricing
= 1.0.4 - 2023-03-03 =
* Fix Custom price
= 1.0.5 - 2023-03-13 =
* Fix Orders View
= 1.0.6 - 2023-04-24 =
* Fix Email
= 1.0.7 - 2023-04-24 =
* Fix Parse Comunas
= 1.0.8 - 2023-05-16 =
* Add Catch Logs Webhooks
= 1.0.9 - 2023-07-20 =
* Fix Weight
= 1.0.10 - 2023-07-27 =
* Catch Logs
= 1.0.11 - 2023-08-22 =
* Fix Weight default 0.010k
= 1.0.12 - 2023-09-07 =
* Add Orders No Blue
= 1.0.13 - 2023-09-13 =
* Add Send orders by status
= 1.0.14 - 2023-09-25 =
* Send order when created
= 1.0.15 - 2023-11-16 =
* add pickup points
= 2.0.0 - 2023-11-17 =
* add bulk functionality and bug fix
= 2.0.1 - 2023-11-24 =
* Fix "Listo para enviar"
= 2.0.2 - 2024-01-09 =
* GoogleKey is added for pickup points, free shipping is added for amounts, agencyId is added to the map when a pickup point has already been selected.
= 2.1.0 - 2024-03-27 =
* Minor error fixes, integration of district functionality 
= 2.1.1 - 2024-05-08 =
* Minor error fixes
= 2.1.2 - 2024-07-24 =
* Minor error fixes
= 2.1.3 - 2024-09-25 =
* Added support for pick-up url for older php versions
= 2.1.4 - 2024-10-29 =
* Fixed the issue where the custom status "Ready to Ship" was not functioning correctly in bulk actions. Now uses the appropriate WooCommerce filters and actions to ensure proper functionality.

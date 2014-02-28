=== Remote Dashboard Notifications ===
Contributors: themeavenue,julien731,SiamKreative
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KADEESTQ9H3GW
Tags: notification,communication,notice
Requires at least: 3.5.1
Tested up to: 3.9
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Have you ever wanted to ask something to your users? Tried to get feedback about your product? This plugin will help you do that.

== Description ==

The plugin works on a server / client basis. The product author uses a WordPress install as the server (where the plugin has to be installed), and the user's WordPress site will be the client.

= How it works =

The plugin is meant to manage messages for multiple products. We call _channels_ the different types of notifications. For instance, if I have 2 products, Product A and Product B, I will create 2 channels in the server: Channel A and Channel B.

Once created, each channel will have its own ID and key used to authenticate the client requests. When integrating RDN to a theme or plugin, the client class will be instantiated with the desired channel ID and key.

When a client site will will check for new notifications, it will make an HTTP request (using WordPress HTTP API) to the server. If the requested channel exists and the key is valid, the server will return the latest notification (if any) in a JSON encoded array.

The client site will then cache this response and display it as an admin notice in the WordPress site dashboard until the user dismisses it.

= Privacy =

The plugin does not collect any information about the client site. The server plugin is completely passive and its only job is to return messages to the requester.

= Translators =

* French (fr_FR) - [SiamKreative](http://profiles.wordpress.org/siamkreative/), [julien731](http://profiles.wordpress.org/julien731/)

== Installation ==

= Integration in a theme or plugin =

It is really easy to integrate this feature in a plugin or theme. Only two steps are required:

1. Copy `includes/class-remote-notification-client.php` into the theme / plugin directory
2. Instanciate the class with the server's URL, a channel ID and key

= Integration examples =

**Theme**

Place this into `functions.php`.

    require( 'class-remote-notification-client.php' );
    $notification = new TAV_Remote_Notification_Client( 35, 'f76714a0a97d1186', 'http://server.url?post_type=notification' );

== Frequently Asked Questions ==

= How can I change the notifications caching delay? =

https://github.com/ThemeAvenue/Remote-Dashboard-Notifications/wiki/Available-Hooks

= How can I change the HTTP request timeout? =

https://github.com/ThemeAvenue/Remote-Dashboard-Notifications/wiki/Available-Hooks

== Screenshots ==

1. All the notification types available
2. List of notification on the server
2. Notification edit screen

== Changelog ==

= 1.0.0 =
* First stable version
=== Sched Event Management Software ===
Contributors: schedllc, codeforthepeople, johnbillion, simonwheatley
Tags: event, events, event management, event manager, calendar
Requires at least: 3.4 
Tested up to: 5.9
Stable tag: trunk
License: GPL v2 or later

Easily manage and promote events! Complete with mobile apps, multiple event calendar views, customization, speaker/sponsor directories and more!

== Description ==

### BRING YOUR EVENTS TOGETHER.

Sched is a full-featured and flexible event management plugin for your unique events. 

Easily create and manage a schedule of events, speakers, sponsors and others with The [Sched Event Management Software](https://sched.com/wordpress/?utm_campaign=wordpress&utm_medium=plugin&utm_source=wordpress) plugin. Whether your events are in-person or virtual events, this plugin boasts professional features backed by our world-class team of developers and designers.

[vimeo https://vimeo.com/451827763]

- [Demo](https://sched.com/wordpress/?utm_campaign=wordpress&utm_medium=plugin&utm_source=wordpress)
- [Test For Free](https://sched.com/signup)
- [Documentation](https://sched.com/support/)
- [Video Tutorials](https://sched.com/support/guide/video-tutorials/)
- [More Resources...](https://resources.sched.com/main-resource)

### WHAT YOU CAN DO

1. Build, customize, and publish your event details in one organized place.
2. Beautifully display your event agenda, speaker directory and key sponsor branding.
3. Engage your attendees with an interactive experience and tools. 
4. Empower your speakers to manage their sessions and promote your events.
5. Collaborate with your team. Work together remotely and in real-time. 
6. Market and grow your event via our web and native apps.

### KEY FEATURES
- Scheduling
- Branded mobile app
- Attendee management
- Session management
- Email invites & announcements 
- Email reminders 
- Speaker management 
- Sponsorship management 
- Exhibit/vendor management
- Session reports
- Capacity and waitlist management
- Check-in management 
- Custom agenda builder 
- Social media promotion 
- Friend finder
- Participant networking profile 
- Feedback reports
- Venue & city maps
- Presentation upload
- Event directory 
- Reporting/Analytics
- API
- CSS customization

== Installation ==

You can install this plugin directly from your WordPress dashboard:

1. Go to the Plugins menu and click “Add New.”
2. Search for “Sched Event Management Software.”
3. Click “Install Now.” 
4. Activate Sched Event Management Software from your Plugins page.

Alternatively, see the guide to [Manually Installing Plugins](https://wordpress.org/support/article/managing-plugins/).

= Usage =

This plugin provides a shortcode which allows you to embed event content from sched.com into your WordPress site.
Due to WordPress security restrictions, Authors and Contributors on your site will be unable to use the standard embed code provided by sched.com. If you use WordPress Multisite then nobody on your site will be able to use the standard embed code at all. This plugin allows you to embed event content from sched.com using a simple shortcode instead.

Add the following shortcode to your post or page content to embed the content from your sched.com event:

`[sched url="http://example.sched.com/"]`

Replace `http://example.sched.com/` with the URL of the event page you wish to embed. You can use the URL of any page of your event on sched.com. Simply copy the URL of the page from sched.com and paste it into your shortcode.

== Frequently Asked Questions ==

= What can I embed with this shortcode? =

You can embed any of your sched.com event pages. Simply copy the URL of the page from sched.com and paste it into your shortcode.

= Can I specify the width of the embed? =

You can specify the width of the embed using the `width` attribute:

`[sched url="http://example.sched.com/" width="500"]`

Note that sched.com only supports widths of 500, 600, 700, 800 and 900 (pixels). The default width is 990 pixels.

= Can I hide the sidebar in the embed? =

You can hide the sidebar in the embed by setting the `sidebar` attribute to 'no':

`[sched url="http://example.sched.com/" sidebar="no"]`

= Can I improve the colour scheme for use on a dark background colour? =

You can specify a colour scheme which is suitable for use on a dark background by setting the `background` attribute to 'dark':

`[sched url="http://example.sched.com/" background="dark"]`

Note that this does not put a dark background behind the schedule, it simply changes the text colour to be suitable for a dark background if your site has one.

= Can I specify the fallback text? =

You can specify the fallback text which will be shown to users who have JavaScript disabled:

`[sched url="http://example.sched.com/"]View my event on sched.com[/sched]`

If you don't specify this, the title of the event page will be used.

== Screenshots ==


== Upgrade Notice ==

= 1.1 =
* Allow any event page from sched.com to be embedded.

== Changelog ==

= 1.1.11 =
* Tested on latest WP version (5.9)

= 1.1.10 =
* Tested on latest WP version (5.7)

= 1.1.9 =
* Renamed plugin to Sched Event Management Software

= 1.1.8 =
* Tested on latest WP version (5.6)

= 1.1.7 =
* Tested on latest WP version (5.3). Readme updates.

= 1.1.6 =
* Tested on latest WP version (5.2.2)

= 1.1.5 =
* Bug fix which prevented the schedule from showing up

= 1.1.4 =
* Tested on latest WP version

= 1.1.3 =
* Made the compiled URL schemaless (//)
* Domain changes (sched.org -> sched.com)
* Allow `[sched.com]` to be used as the shortcode

= 1.1.2 =
* Version bump

= 1.1.1 =
* Sched.com taking over the ownership over plugin.

= 1.1 =
* Allow any event page from sched.com to be embedded.

= 1.0.1 =
* Allow `[sched.org]` to be used as the shortcode in addition to `[sched]`.

= 1.0 =
* Initial release.

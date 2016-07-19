=== Plugin Name ===
Contributors: stephend
Donate link: http://www.wandlesoftware.com/products/open-source-software/wordpress-smart-app-banner-plugin
Tags: ios, iphone, ipad, smart, app, banner, apple
Requires at least: 3.1.4
Tested up to: 4.1.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a WordPress plugin that allows you to use Smart App Banners, introduced in
iOS 6, with your WordPress blog.

== Description ==

This is a WordPress plugin that allows you to use the Smart App Banners with your
WordPress blog. 

[According to Apple](https://developer.apple.com/library/ios/#documentation/AppleApplications/Reference/SafariWebContent/PromotingAppswithAppBanners/PromotingAppswithAppBanners.html#//apple_ref/doc/uid/TP40002051-CH6-SW1), Smart App Banners:

> vastly improve users’ browsing experience compared to other promotional methods.
> As banners are implemented in iOS 6, they will provide a consistent look and
> feel across the web that users will come to recognize. Users will trust that tapping the
> banner will take them to the App Store and not a third-party advertisement. They will
> appreciate that banners are presented unobtrusively at the top of a webpage, instead of
> as a full-screen ad interrupting the web content. And with a large and prominent
> close button, a banner is easy for users to dismiss.

It's really simple to use. In short, you download and activate the plugin. On pages and posts you should find a "Smart App Banner" settings box. If you want the Smart App Banner to appear on this page then enter the App ID of your application here. You can also enter affiliate data and an app argument here. 

If you want to display a banner on the home page there's a setting screen (Settings -> Smart App Banner) where you can enter the App ID.

You can find the App ID in iTunes Connect, using the
[iTunes Link Maker](http://itunes.apple.com/linkmaker/) or if the iTunes URL for your
app looks like this:

http://itunes.apple.com/us/app/rootn-tootn-baby-feed-timer/id530589336?ls=1&mt=8

Then your ID is "530589336".

The other two fields are optional.

The affiliate data field varies depending on the affiliate. The most common is PHG, where the value looks like "at=AFFILIATE_TOKEN" or "at=AFFILIATE_TOKEN&ct=CAMPAIGN" (without the quotes). You can find the token when you sign into the PHG website. The campaign is just some text you use to identify a particular marketing campaign.

So I might have "at=11lmMT&ct=wordpress" on the product pages of my website. Check the documentation to find your affiliate token and confirm the format.

[According to the documentation](https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/PromotingAppswithAppBanners/PromotingAppswithAppBanners.html), the app argument value is:

> A URL that provides context to your native app. If you include this, and the user has your
> app installed, she can jump from your website to the corresponding position in your iOS app.

This plugin does not restrict or validate what you put here.

> You can format it however you'd like, as long as it is a valid URL.

== Installation ==

1. Upload `wsl-smart-app-banner.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add your App ID on the home page and/or specific pages or posts

== Screenshots ==

1. Safari on iOS

== Frequently Asked Questions ==

= I can't see the banner! =

It's only available on iOS6 devices. if you want to check that it's working and you
don't have a device, you can "View Source" on your page and look for the text
"apple-itunes-app".

= I've specified an App ID for my iPad app but I don't see the banner =

Where did you put the App ID? Or in the App ID field or in the "App ID (iPad)" field?

If you only have one app -- iPad-only or Universal -- you only need to specify the
"App ID." The "App ID (iPad)" field is for when you have two apps of the same name,
one for the iPhone and one for the iPad. You should put the default (iPhone) ID in
the top field and the iPad App ID in the other one.

= I found a bug! =

The quickest way is to see if you can fix it yourself. I accept patches! But if
you can't, please let me know and I'll see what I can do. 

= I really need it to do x =

I accept patches! I'm also happy to hear your suggestions but I can't promise anything.

= Does it really need WordPress version 3.1.4? =

Probably not. But since I always keep my installations up to date I have no way of testing on older versions. I believe it should work going back all the way to 1.5.1, but I've not tried. Let me know if you get it working.

= It's really great! How can I ever thank you?! =

You can always buy my apps. Have a look at http://www.wandlesoftware.com/. Or
there's a donate link at the same site. Or if you can write code, I accept
good patches.

== Upgrade Notice ==

If you're upgrading from version 0.1 you'll need to add your App IDs again I'm afraid. You can remove the old wsl-app-id custom field.

== Changelog ==

= 1.1.0 =
* Add dropdown list of apps

= 1.0.0 =
* Localisation support
* Translation into Serbo-Croation (sr_RS) thanks to [Borisa Djuraskovic](http://www.webhostinghub.com/)

= 0.4.2 =
* Option to use the same Smart App Banner on all pages

= 0.4.1 =
* Fixed: Occasionally displayed the iPad version of the meta code when no iPad App ID was specified

= 0.4 =
* New option: if you specify an iPad App ID, it'll display that only on iPads. It does the check on the device itself, so the code is "safe" even if you use WordPress caching plugins

= 0.3 =
* Updates to reflect public nature of iOS 6
* Add affiliate data and app argument

= 0.2.1 =
* Show admin box for all page types, not just page and post

= 0.2 =
* Option to display a banner on the home page
* Settings box rather than having to enter custom fields manually
* Fix 'Warning: Missing argument 1 for get_page()' error

= 0.1 =
* Initial version


=== Profile CCT ===
Contributors: enej, ejackisch, alekarsovski
Tags: profile, user
Requires at least: 3.2
Tested up to: 3.3.1

Manage and display advanced user profiles on your website.

== Description ==

This plugin allows for management of user public profiles with a lot of customizability.

In the dashboard under Profiles->Settings, administrators can:

* Build a form for users to fill out, choosing which fields to include and how to arrange them, via a straightforward drag and drop interface.
* Add taxonomies to group and filter profiles
* Design a page to display user information
* Design a separate list view when listing multiple users
* Create additional fields that can be added to the profile form and page.
* Manage, create and delete user profiles

Users can edit their profile under Users->Public Profile, where they'll be presented with
the form you created.

Everything can be styled with CSS

Dashboard icon from http://p.yusukekamiyamane.com/ 

Social icons from http://paulrobertlloyd.com/2009/06/social_media_icons/ 

== Installation ==

1. Extract the zip file into wp-content/plugins/ in your WordPress installation
2. Go to plugins page to activate

== Usage ==

The plugin will generate pages for individual profiles as well as for lists of people.

For further flexibility you can use the [profilelist] shortcode to display profiles anywhere on a site.

=[profilelist] shortcode=

[profilelist] by default shows all profiles in list view format

Filtering by taxonomy:
[profilelist {taxonomyname} = "{taxonomyvalue}"] shows profiles that meet the specified criteria

You can specify multiple taxonomies to filter by, by default then only profiles that meet ALL the specified criteria will be displayed. Alternatively you can also add query="or" to the shortcode to show profiles meeting at least one of the criterias.

Displaying more details:
use display="full" to show full profiles, or display="name" to only shows names. Default behaviour shows the list view as set in the settings.

== Change log ==
= Version 1.1.3 =
* Added [profilelist] shortcode.

= Version 1.1.2 =
* Removing dead code, webscraper was implemented as db-field

= Version 1.1.1 =
* Bug fixes: social fields look better
* Appearance of professional affiliation changed
* Renamed classed so that they are not repeated, use shell-rename
* added the ability to sort fields now by using the page order
* clarification of the permission fields

= Version 1.1 =
* Initial public release
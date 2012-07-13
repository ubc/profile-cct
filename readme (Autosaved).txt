=== Profile CCT ===
Contributors: enej, ejackisch, alekarsovski,  ctlt-dev, ubcdev
Tags: profile, user
Requires at least: 3.2
Tested up to: 3.4.1
Stable tag: 1.2

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

For further flexibility you can use the [profilelist] and [profile] shortcodes to display profiles anywhere on a site.

= [profilelist] shortcode =

[profilelist] by default shows all profiles in list view format

Filtering by taxonomy:
[profilelist {taxonomyname} = "{taxonomyvalue}"] shows profiles that meet the specified criteria

You can specify multiple taxonomies to filter by, by default then only profiles that meet ALL the specified criteria will be displayed. Alternatively you can also add query="or" to the shortcode to show profiles meeting at least one of the criterias.

Filtering by letter:
[profilelist letter="a"] returns all profiles where last name starts with letter 'a'.

Ordering results:
[profilelist orderby = orderfield] where 'orderfield' is either first_name, last_name, or date. By default it will use the manual ordering specified on the Profiles->Order Profiles page
You can also show results in descending order, eg [profilelist orderby='first_name' order='desc']


Displaying more details:
use display="full" to show full profiles, or display="name" to only shows names. Default behaviour shows the list view as set in the settings.


Show a specific set of people:
[profilelist include="id, id2, id3..."]
Displays the people with the corresponding id

= [profile] shortcode =

With this shortcode you can display a single profile. (This can also be accomplished with the [profilelist] shortcode with the right parameters, but this is a more straightforward option)

Simply use [profile person="slug"] where slug is usually firstname-lastname.

By default the full view will be shown, but you can set display="list" instead to show the list view.

= [profilesearch] shortcode =

Display a search box (with jquery-ui Autocomplete) to search for profiles by name

== Change log ==
= Version 1.2 =
* added [profilesearch] shortcode
* added automatic ordering (first name, last name, date added) for archive pages and shortcode
* profiles can now be filtered by first letter of last name
* added filter/search interface on archive page
* fixed a bug that may cause PHP errors when [profilelist] is called with no arguments
* fixed image uploader to be compatible with WordPress 3.4 
* fixed minor formatting issues

= Version 1.1.8.1 =
* Version Number bump
= Version 1.1.8 =
* made it so that you can place the plugin into which every folder. 
* better error on settings page
* Bug fix for the ordering of the items

= Version 1.1.7 =
* bug fix for IE7 Tabs didn't display properly

= Version 1.1.6 =
* bug fixes, for ordering of the items

= Version 1.1.5 =
* added the ability to order things using a better UI

= Version 1.1.4 =
* javascript Bug fixes
* css bug fixes
* input fields bug fixes
* just lots of bug fixes
* bug fix, now admin is able to change the proper author
* bug fix shortcode is lists all the people by default


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
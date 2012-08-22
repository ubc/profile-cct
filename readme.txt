=== Profile CCT ===
Contributors: enej, ejackisch, alekarsovski,  ctlt-dev, ubcdev
Tags: profile, user
Requires at least: 3.2
Tested up to: 3.4.1
Stable tag: 1.2.1

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
3. Recommended: Some minor changes to theme files (archive.php, search.php, taxonomy.php) to accommodate profile cct features (see "Usage" for details)

The plugin (optionally) makes use of jQuery UI tabs so you'll need to grab some CSS for that if your theme doesn't already have it and you want tabbed content on profile pages.
See http://jqueryui.com/themeroller/ to find or create a style for the tabs

== Usage ==

The plugin will generate pages for individual profiles as well as for lists of people.

The profile form your users will fill out can be fully customized in Profiles->Settings, as well as how profiles are displayed on your site.

Additional information on this customization is available at http://wiki.ubc.ca/Documentation:UBC_Content_Management_System/Managing_People_Profiles_and_Directories

= Archive Pages =

By default you can see a list of profiles on your site at example.com/your-site-path/person

To display filtering/searching controls on the archive page you have three options:
1. modify your taxonomy.php template and archive.php (or archive-profile_cct.php) in your theme folder and include the line <?php do_action("profile_cct_display_archive_controls"); ?> where you want the controls to appear. The plugin will function fine without this but it won't be as easy for your users to search/filter/browse profiles. (The controls can be customized as well on the Settings page)
2. Use the Profile navigation widget. It'll include the fields you specify in the settings page.
3. Use the [profilenavigation] shortcode (More info in the shortcode section of this document.

**Note: Make sure you enable some navigation elements in the Profiles->Settings page under the Settings tab**


In addition, you may want to customize the search results page for profile_cct posts and only display the_excerpt() in the loop (the_excerpt() will output the list view as set on the profile settings page)

See the examples folder for examples of these theme modifications

For other uses you can use the [profilelist] and [profile] shortcodes to display profiles anywhere on a site.

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

= [profilenavigation] shortcode =

Displays profile navigation. If no parameters are supplied it'll rely on the options set in the settings page. If at least one parameter is supplied then the global settings will be ignored
display_searchbox=true to show the search box
display_alphabet=true to show the letter list
display_orderby=true to show the orderby field
display_tax="comma separated list of taxonomies" to show dropdowns to filter by those taxonomies.

eg [profilenavigation display_searchbox="true" display_tax="location, position"] will show a searchbox as well as two dropdown menus to filter by the two specified taxonomies

== Screenshots ==

1. Listing profiles in the dashboard
2. A profile form for the user to fill out, fully customizable in the settings
3. Social network links, custom taxonomies, etc
4. Custom taxonomies for profiles can be easily set up to filter profiles by.
5. Drag and Drop profiles around to customize how they're ordered. Alternatively they can be sorted automatically by first name, last name, date
6. Main plugin settings screen. Tabs at the top to access various settings
7. Settings tab containing general settings 
8. Custom taxonomies will show up in the menu in the dashboard
9. The Form Builder where you can set up a profile form for your users to fill out by dragging and dropping the desired fields into place
10. Profile View Builder where you can decide what shows up on users' profile pages.
11. List View Builder
12. Custom fields can be added which can then be added to the form and the views
13. Where users go to edit their profile

== Change log ==

= Version 1.2.2 =
* Removed php short tags
* Ensure profile data gets updated appropriately when plugin is updated

= Version 1.2.1 =
* Fixed bug where post formatting gets mangled sometimes
* Fixed bug where default values sometimes show up in profile pages

= Version 1.2 =
* added [profilesearch] shortcode
* added automatic ordering (first name, last name, date added) for archive pages and shortcode
* profiles can now be filtered by first letter of last name
* added filter/search interface on archive page
* also added widget and shortcode to display filter/search interface
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
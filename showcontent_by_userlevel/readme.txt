=== Show Content by User Level ===
Contributors: nicholascaporusso
Donate link: http://www.nicholascaporusso.com
Plugin URI: http://www.nicholascaporusso.com/showcontent-by-userlevel/
Tags: permission, role, content, user-level
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: trunk

This simple plug-in hides content from all users except those that exceed a specific user level. 

== Description ==

This plug-in hides a specific part of the content of a page (or post) to all users whose user level is below a required user level.

Therefore, if a content is visible to user level X:
1. if X > 0 users who are not registered will not be able to access that specific content;
2. users whose user level is less than X will not be able to access that specific content;
3. users whose user level is equal or greater than X will be able to access that specific content.

To hide a specific content, use the following syntax to encapsulate the hidden content:
[hide {level}] {content} [hide {level}]
where {level} is the NUMBER corresponding to the user level, and {content} is the part of you want to hide.

Example:
[hide 0] You will always see this content [hide 0]
[hide 1] You will not see this if you are not logged as subscriber [hide 1]
[hide 2] You will not see this if you are not logged as contributor [hide 2]
[hide 3] You will not see this if you are not logged as author [hide 3]
[hide 4] You will not see this if you are not logged as editor [hide 4]
[hide 5] You will not see this if you are not logged as administrator [hide 5]

Fancy example:

[hide 1] You will see this if you are logged as subscriber [hide 5], but you will not see this if you are not logged as administrator [hide 5][hide 1]

For further help, please visit http://www.nicholascaporusso.com/showcontent-by-userlevel/ 
== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `showcontent_by_userlevel.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Encapsulate the content you want hidden by using the syntax [hide {level}] {content} [hide {level}]
4. Remember: {level} is a NUMBER corresponding to the user level who CAN can view {content}.

== Frequently Asked Questions ==

= Questions? =

Post a comment to: http://www.nicholascaporusso.com/showcontent-by-userlevel/

== Screenshots ==

1. None yet.
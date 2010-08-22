=== WordPress DF-style Linked List Plugin ===
Contributors: yjsoon TO=DO
Donate link: http://yjsoon.com/df-linked-list
Tags: links, rss
Requires at least: 2.8
Tested up to: 3.0
Stable tag: ???TODO

Make your RSS feed for linked-list posts behave like Daring Fireball's (item's RSS permalink goes to link, and other modifications).

== Description ==

This plugin makes your RSS feed for linked-list posts (indicated using a custom field) behave like [Daring Fireball](http://daringfireball.net). To use, set the custom field "linked_list_url" to the desired location on a link post. In your RSS feed, the following will happen: (1) the item's RSS permalink becomes the link destination; (ii) the actual permalink to your post is inserted as a star glyph at the end of your post; and (iii) a star glyph is added in front of your non-linked-list post titles. Behaviour is customisable in options.

For themers, the plugin also provides functions to customise your design by checking if the item is a linked list item, getting a permalink with glyph, etc. 

Adapted from Jonathan Penn's [Wordpress Linked List plugin](http://github.com/jonathanpenn/wordpress-linked-list-plugin). 

== Installation ==

1. Upload `linked-list.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Customise in options.

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 0.1 =
Forked from Jonathan Penn's version.

`<?php code(); // goes in backticks ?>`
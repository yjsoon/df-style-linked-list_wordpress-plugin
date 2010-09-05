=== WordPress DF-style Linked List Plugin ===
Contributors: yjsoon
Donate link: http://yjsoon.com/dfll-plugin
Tags: links, rss, wordpress, linkblogs, linked-list
Requires at least: 2.7
Tested up to: 3.0
Stable tag: 2.0

Make your RSS feed for linked-list posts behave like Daring Fireball's: item's RSS permalink goes to link, and other modifications.

== Description ==

This plugin makes your RSS feed for linked-list posts (indicated using a custom field) behave like [Daring Fireball](http://daringfireball.net). 

To use, set the custom field "linked_list_url" to the desired location on a link post. In your RSS feed, the following will happen: 

(i) the item's RSS permalink becomes the link destination; 
(ii) the actual permalink to your post is inserted as a star glyph at the end of your post; and 
(iii) a star glyph is added in front of your non-linked-list post titles. Behaviour is customisable in options.

All three parts are customizable, and you can use different glyphs or text if you'd like. For theme designers, the plugin also provides functions (get_the_permalink_glyph(), the_permalink_glyph(), get_the_linked_list_link(), the_linked_list_link(), get_glyph() and is_linked_list()) to customise your design by checking if the item is a linked list item, getting a permalink with glyph, etc.

Adapted from Jonathan Penn's [Wordpress Linked List plugin](http://github.com/jonathanpenn/wordpress-linked-list-plugin). 

== Installation ==

* Upload `linked-list.php` to the `/wp-content/plugins/` directory
* Activate the plugin through the `Plugins` menu in WordPress
* Customise extra text in your link titles and body in Settings -> DF-Style Linked List.
* Templates can be customised by using the provided functions to check whether your are rendering a linked list item or not. For example:
<code>
   ....the top part of a template....
   <?php if (have_posts()): ?>
      <?php while (have_posts()) : the_post(); ?>

        <?php if (is_linked_list()): ?>

          ...other HTML formatting...
          <a href="<?php the_linked_list_link()">This is a linked list link</a>
          ...other HTML formatting...

        <?php else: ?>

          ...other HTML formatting...
          <a href="<?php the_link()">This is a normal post link</a>
          ...other HTML formatting...

        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>
    ...the rest of the template...
</code>
* Basically, use the `is_linked_list()` function to check. And then alter your template the way you wish to make it look or act differently.
* Other functions you can use are `get_the_permalink_glyph()`, `the_permalink_glyph()`, `get_the_linked_list_link()`, `the_linked_list_link()` and `get_glyph()`.
* For more information about customizing wordpress templates, view the "Template Tags" document on the [Wordpress Codex](http://codex.wordpress.org/Template_Tags)

== Usage ==

When adding a link, create a normal blog post, but add a custom field "linked_list_url" with the desired link URL. The RSS feed item will automatically point to that URL.

== Frequently Asked Questions ==

= Why doesn't my RSS feed change immediately? =

The changes could take a while to show up. Google Reader took a day to register changes in my feed settings. If you want to test, add your RSS feed to [My Yahoo](http://my.yahoo.com) and refresh it with the "options" pane -- this updated the most reliably for me while testing.

== Changelog ==

= 2.0 =
* Initial public release on WordPress plugins
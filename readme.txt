=== Daring Fireball-style Linked List Plugin ===
Contributors: yjsoon
Donate link: http://yjsoon.com/dfll-plugin
Tags: links, rss, wordpress, linkblogs, linked-list
Requires at least: 2.7
Tested up to: 3.1
Stable tag: 2.7.4

Make your RSS feed for linked-list posts behave like Daring Fireball's: item's RSS permalink goes to link, and other modifications.

== Description ==

This plugin makes your RSS feed behave like Daring Fireball's linked list posts, and has some extra features to make posting linked lists easier. Also supports Twitter Tools.

_Part One_

Makes your RSS feed for linked-list posts (indicated using a custom field) behave like [Daring Fireball](http://daringfireball.net). 

To use, set the custom field "linked_list_url" to the desired location on a link post. In your RSS feed, the following will happen: 

(i) the item's RSS permalink becomes the link destination; 
(ii) the actual permalink to your post is inserted as a star glyph at the end of your post; and 
(iii) a star glyph is added in front of your non-linked-list post titles. Behaviour is customisable in options.

All three parts are customizable, and you can use different glyphs or text if you'd like. For theme designers, the plugin also provides functions (get_the_permalink_glyph(), the_permalink_glyph(), get_the_linked_list_link(), the_linked_list_link(), get_glyph() and is_linked_list()) to customise your design by checking if the item is a linked list item, getting a permalink with glyph, etc.

Adapted from Jonathan Penn's [Wordpress Linked List plugin](http://github.com/jonathanpenn/wordpress-linked-list-plugin). 

_Part Two_

Add link from post content. This feature allows you to set the custom field "linked_list_url" from within the post content. This is especially handy for using with the 'Press This' bookmarklet.

When you activate this feature, the DFLL plugin will look at the first line of your post content for a link anchor, and it'll set that link as the linked_list_url for your post. For example, the following post content:

> &lt;a href='http://google.com'&gt;Google!!!&lt;/a&gt;.
> This is a link post to Google.

... will have its first line removed, the URL http://google.com passed into the custom field linked_list_url, and will have its first line removed to just end up with the text 'This is a link post to Google'. The text in the anchor ('Google!!!') will be ignored.

It's very important to note three requirements: (i) the anchor tag must be in the first line of the post, (ii) the tag must be the only element on that line, and (iii) the line must end in a period. This is the syntax that the 'Press This' bookmarklet uses, so you can just hit 'Press This' and enter to go to the next line and stop typing.

Any text in the anchor will be ignored, and the entire first line will be discarded. This also means that if, for whatever, reason, you like posting link anchors that end in periods as the first line of your blog, you shouldn't activate this checkbox, or you'll end up with linked list posts by accident!

This was adapted from [CF Setter by Justin Blanton](http://hypertext.net/projects/cfsetter). 

_Twitter Tools support_

If you're using [Twitter Tools](http://crowdfavorite.com/wordpress/plugins/twitter-tools/), you can customise your tweets to have your custom glyph or text appear before either your "regular" or linked-list posts.

Questions or suggestions? Look me up on [Twitter](http://twitter.com/yjsoon).  

== Installation ==

* Upload `linked-list.php` to the `/wp-content/plugins/` directory
* Activate the plugin through the `Plugins` menu in WordPress
* Customise extra text in your link titles and body in Settings -> DF-Style Linked List.
* To customize the theme, use the child theme provided, or check out my [theme customization instructions](http://yjsoon.com/2011/02/customising-your-wordpress-theme-for-the-df-linked-list-plugin).
* Some basic instructions follow: Templates can be customised by using the provided functions to check whether your are rendering a linked list item or not. For example:
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
* Essentially, use the `is_linked_list()` function to check, then alter your template the way you wish to make it look or act differently.
* Other functions you can use are `get_the_permalink_glyph()`, `the_permalink_glyph()`, `get_the_linked_list_link()`, `the_linked_list_link()` and `get_glyph()`.
* For more information about customizing wordpress templates, view the "Template Tags" document on the [Wordpress Codex](http://codex.wordpress.org/Template_Tags)
* To enable the first link and Twitter Tools functionality or find out more, turn them on in the checkbox under Settings.

== Usage ==

* When adding a link, create a normal blog post, but add a custom field "linked_list_url" with the desired link URL. The RSS feed item will automatically point to that URL.
* When posting, to insert a link without setting the custom field manually, put your URL wrapped in an anchor tag in the first line, ending with a period. For example: &lt;a href="http://yjsoon.com"&gt;Doesn't matter what's in here&lt;/a&gt;.

== Frequently Asked Questions ==

= Why doesn't my RSS feed change immediately? =

The changes could take a while to show up. Google Reader took a day to register changes in my feed settings. If you want to test, add your RSS feed to [My Yahoo](http://my.yahoo.com) and refresh it with the "options" pane -- this updated the most reliably for me while testing.

= Why doesn't the new link posting work? =

You have to enable it in the options page, and also make sure you insert an anchor tag link on the first line which __ends in a period (dot)__.

= This doesn't work! My blog looks the same! =

You still have to customize your theme to make it look right. Please refer to [this blog entry](http://yjsoon.com/2011/02/customising-your-wordpress-theme-for-the-df-linked-list-plugin) for more information.

== Changelog ==

= 2.0 =
* Initial public release on WordPress plugins

= 2.5 =
* Added functionality to set the linked list URL custom field with an anchor tag link in the first line.
* Removed the ability to post a linked list URL with [ll] and [/ll]. If you'd like to do that, use Justin Blanton's [CF Setter](http://hypertext.net/projects/cfsetter) instead.

= 2.5.4 =
* Critical stability update -- the previous version was causing some issues on certain installs. If you have any problems, please look for me on [Twitter](http://twitter.com/yjsoon). 
* Fixed permalink glyph not appearing.

= 2.6 =
* CDATA fix -- RSS should now validate. Thanks to @pyrmont for the fix.

= 2.7 =
* Twitter Tools support.

= 2.7.1 =
* Bugfix for newlines not appearing when using post with first-line.

= 2.7.3 =
* Included child theme and instructions.

== Upgrade Notice ==

= 2.5.1 =
This version adds a major new feature -- you can now set the linked_list_url field without going into WordPress's custom fields menu. See [plugin homepage](http://yjsoon.com/dfll-plugin) for details.

= 2.5.2 =
Critical stability update -- the previous version was causing some issues on certain installs. If you have any problems, please look for me on [Twitter](http://twitter.com/yjsoon). 

= 2.5.3 =
Bugfix for permalink glyph not showing up at end of posts. Sorry about that.

= 2.6 =
* CDATA fix -- RSS should now validate. Thanks to @pyrmont for the fix.

= 2.7 =
* Twitter Tools support.

= 2.7.3 =
* Included child theme and instructions.

== License ==

Copyright (c) 2010-2011 YJ Soon

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

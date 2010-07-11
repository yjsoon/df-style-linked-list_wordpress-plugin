<?php
/**
 * @package DF Linked List
 * @author YJ Soon
 * @version 1.1
 */
/*
Plugin Name: Linked List
Plugin URI: http://github.com/yjsoon/wordpress-linked-list-plugin
Description: Adapted from Jonathan Penn's <a href="http://github.com/jonathanpenn/wordpress-linked-list-plugin">linked-list plugin</a> to make your blog's RSS feed behave even more like <a href="http://daringfireball.net">Daring Fireball</a>. To use, set the custom field "linked_list_url" to the desired location on a link post. In your RSS feed, the following will happen: (1) the permalink becomes the link destination; (ii) the actual permalink to your post is inserted as a star glyph at the end of your post; (iii) a star glyph is added to before your non-linked-list post titles. Also provides functions to customise your design by checking if the item is a linked list item, getting a permalink with glyph, etc. 
Author: Yinjie Soon
Version: 1.1
Author URI: http://yjsoon.com
*/

// Called to see if the current post in the loop is a linked list
function is_linked_list()
{
  global $wp_query;
  $postid = $wp_query->post->ID;
  $url = get_post_meta($postid, 'linked_list_url', true);
  return (!empty($url));
}

function get_the_linked_list_link()
{
  $url = get_post_custom_values("linked_list_url");
  return $url[0];
}

function the_linked_list_link()
{
  echo get_the_linked_list_link();
}

// This just echoes the chosen line, we'll position it later
function ensure_rss_linked_list($value) {
  if (is_linked_list()) {
    echo get_the_linked_list_link();
  } else {
    echo $value;
  }
}

// Now we set that function up to execute when the admin_footer action is called
add_action('the_permalink_rss', 'ensure_rss_linked_list');


/*-----------------------------------------------------------------------------
  Enhanced linked list functions
-----------------------------------------------------------------------------*/

// To display a permalink glyph
function the_permalink_glyph()
{
  echo get_the_permalink_glyph();
}

function get_the_permalink_glyph()
{
  return '<a href="' . get_permalink() . '" rel="bookmark" title="Permanent link to \''.get_the_title().'\'" class="glyph">&nbsp;'. get_glyph() .'&nbsp;</a>';
}

function get_glyph() {
  return '&#9733;';
}

// Inject permalink glyph into RSS feed contents
function insert_permalink_glyph_rss($content)
{
  if (is_linked_list() && is_feed()) 
  {
    $content = $content . "<p>" . get_the_permalink_glyph() . "</p>\n";
  }
  return $content;
}
add_filter('the_content', 'insert_permalink_glyph_rss');
add_filter('the_excerpt_rss', 'insert_permalink_glyph_rss');

// Inject permalink glyph into RSS title
function insert_title_glyph_rss($title)
{
  if (!is_linked_list()) 
  {
    $title = get_glyph() . " " . $title;
  }
  return $title;
}
add_filter('the_title_rss', 'insert_title_glyph_rss');


?>

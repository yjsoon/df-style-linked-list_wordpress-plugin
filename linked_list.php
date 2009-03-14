<?php
/**
 * @package Linked List
 * @author Jonathan Penn
 * @version 1.0
 */
/*
Plugin Name: Linked List
Plugin URI: http://wavethenavel.com/projects/wordpress-linked-list-plugin
Description: Have designated posts act like the "Linked List" posts on Daring Fireball. Set the custom field "linked_list_url" to any desired destination on a post. The permalink then becomes that destination. Affects RSS feeds automatically and provides functions you can use in your template to control the layout if the post is a normal post, or a linked-list post".
Author: Jonathan Penn
Version: 1.0
Author URI: http://wavethenavel.com
*/

// Called to see if the current post in the loop is a linked list
function is_linked_list()
{
  $url = get_post_custom_values('linked_list_url');
  if (empty($url)) {
    return false;
  } else {
    return true;
  }
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

?>

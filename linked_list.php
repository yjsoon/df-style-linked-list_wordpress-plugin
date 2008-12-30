<?php
/**
 * @package Linked List
 * @author Jonathan Penn
 * @version 1.0
 */
/*
Plugin Name: Linked List
Plugin URI: http://wavethenavel.com/projects/wordpress-linked-list-plugin
Description: Have designated posts act like the "Linked List" posts on Daring Fireball. Set the custom field "linked_list_url" to any desired destination on a post. The permalink then becomes that destination. Affects both templates and RSS feeds.
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

// This just echoes the chosen line, we'll position it later
function ensure_rss_linked_list($value) {
  $url = get_post_custom_values('linked_list_url');
  if (is_linked_list()) {
    echo $url[0];
  } else {
    echo $value;
  }
}

// Now we set that function up to execute when the admin_footer action is called
add_action('the_permalink_rss', 'ensure_rss_linked_list');
add_action('the_permalink', 'ensure_rss_linked_list');

?>

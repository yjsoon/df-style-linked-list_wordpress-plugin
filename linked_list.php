<?php
/**
 * @package DF-Style Linked List
 * @author YJ Soon
 * @version 1.1
 */
/*
Plugin Name: DF-Style Linked List
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


/*-----------------------------------------------------------------------------
  Options menu functions
-----------------------------------------------------------------------------*/


add_action('admin_menu', 'dfll_menu');

function dfll_menu() {
  add_options_page('DF-Style Linked List Options', 'DF-Style Linked List', 'manage_options', 'dfll-options', 'dfll_options');
}

function dfll_options() {

  // check that the user has the required capability 
  if (!current_user_can('manage_options'))
  {
    wp_die('You do not have sufficient permissions to access this page.');
  }

  // variables for the field and option names 
  
  $opt_name = 'mt_favorite_color';
  $hidden_field_name = 'mt_submit_hidden';
  $data_field_name = 'mt_favorite_color';

  // Read in existing option value from database
  $opt_val = get_option( $opt_name );

  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
      // Read their posted value
      $opt_val = $_POST[ $data_field_name ];

      // Save the posted value in the database
      update_option( $opt_name, $opt_val );

      // Put an settings updated message on the screen

  ?>
  <div class="updated"><p><strong><?php echo 'Your settings have been saved.'; ?></strong></p></div>
  <?php

  }

  // Now display the settings editing screen

  ?>
  
  <div class="wrap">
  <h2>Daring Fireball-Style Linked List Plugin Settings</h2>

  <form name="df-form" method="post" action="">

    <h3>Glyphs</h3>
    <p>Glyphs are symbols (HTML entities) that can be displayed by most browsers. I've picked some out that you can choose from &mdash; &#9733; &#8594; &#8658; &nabla; &loz; &#10004; &#10010; &#10020; &#10022; &#9819; &#9820; &mdash; just copy and paste into the fields below. Alternatively, 
      pick one up from <a href="http://www.danshort.com/HTMLentities/index.php?w=maths">this website</a>.</p>

    <h3>Linked list properties</h3>
    <p>This section defines the behaviour of RSS entries of linked list posts. Default behaviour follows Daring Fireball.</p>

    <!-- TODO: Check boxes aren't saved / presented properly? -->
    <p><input type="checkbox" name="link_goes_to" <?php $a = get_option('link_goes_to'); echo ($a=="") ? "checked" : $a;?> /> <strong>RSS link goes to linked item</strong>: Linked list entries point to the linked item in question, i.e. when you click on the link title in your RSS reader, your browser goes straight to that link.</p>
    <p><input type="checkbox" name="show_glyph_after" <?php $a = get_option('show_glyph_after'); echo ($a=="") ? "checked" : $a;?> /> <strong>Show permalink text after linked list entries:</strong> At the bottom of each linked list blog post, there's a permalink bringing you back to your blog post. On DF, this is the star glyph.</p>
    <p>Permalink Text <input type="text" name="glyph_type" value="<?php $a = get_option('glyph_type');  echo ($a=="") ? "&#9733;" : $a; ?>" /></p>

    <!-- TODO: Can't print? -->
    <?php echo get_option('link_goes_to'); ?>

    <h3>Title properties</h3>
    <p><input type="checkbox" name="show_glyph_before" value="<?php echo get_option('show_glyph_before'); ?>" /> Show glyph in front of non-linked-list article titles</p>
    
  <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

  <p><?php echo "Glyph:"; ?> 
  <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
  </p><hr />

  <p class="submit">
  <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
  </p>

  </form>
  </div>

  <?php

}

?>
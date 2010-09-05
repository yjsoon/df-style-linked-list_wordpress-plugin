<?php
/*
Plugin Name: DF-Style Linked List
Plugin URI: http://github.com/yjsoon/wordpress-linked-list-plugin
Description: Make your blog's RSS feed behave like <a href="http://daringfireball.net">Daring Fireball</a>. To use, set the custom field "linked_list_url" to the desired location on a link post. See "DF-Style Linked List" under WordPress Settings for more options.
Author: Yinjie Soon
Version: 2.0
Author URI: http://yjsoon.com
*/


/*-----------------------------------------------------------------------------
  For theme developers - these should be all you need to refer to
-----------------------------------------------------------------------------*/

// To display a permalink around the glyph
function get_the_permalink_glyph() {
  return '<a href="' . get_permalink() . '" rel="bookmark" title="Permanent link to \''.get_the_title().'\'" class="glyph">'. get_glyph() .'</a>';
}
function the_permalink_glyph() {
  echo get_the_permalink_glyph();
}

// To display the linked list URL
function get_the_linked_list_link() {
  $url = get_post_custom_values("linked_list_url");
  return $url[0];
}
function the_linked_list_link() {
  echo get_the_linked_list_link();
}

// Just returns the glyph (this is set in the option under "Text for permalink")
function get_glyph() {
  $options = get_option('dfll_options');  
  return $options['glyph_after_post_text'];
  //return '&#9733;';
}

// Called to see if the current post in the loop is a linked list
function is_linked_list() {
  global $wp_query;
  $postid = $wp_query->post->ID;
  $url = get_post_meta($postid, 'linked_list_url', true);
  return (!empty($url));
}


/*-----------------------------------------------------------------------------
  RSS modification handling functions
-----------------------------------------------------------------------------*/

// Echoes the linked list link if it should
function ensure_rss_linked_list($value) {
  $options = get_option('dfll_options');
  if ($options['link_goes_to'] && is_linked_list()) {
    echo get_the_linked_list_link();
  } else {
    echo $value;
  }
}

// Now set function up to execute when the admin_footer action is called
add_action('the_permalink_rss', 'ensure_rss_linked_list');


// Inject permalink glyph into RSS feed contents
function insert_permalink_glyph_rss($content) {
  $options = get_option('dfll_options');
  if (is_linked_list() && is_feed()) {
    if ($options['glyph_after_post']) {
      $content = $content . "<p>" . get_the_permalink_glyph() . "</p>\n";
    }
  }
  return $content;
}
add_filter('the_content', 'insert_permalink_glyph_rss');
add_filter('the_excerpt_rss', 'insert_permalink_glyph_rss');

// Inject permalink glyph into RSS title
function insert_title_glyph_rss($title) {
  $options = get_option('dfll_options');
  if (!is_linked_list() && $options['glyph_before_blog_title']) { // if normal blog title
    $title = $options['glyph_before_blog_title_text'] . " " . $title;
  }
  elseif (is_linked_list()) { // if linked list title
    if ($options['glyph_before_link_title']) $title  = $options['glyph_before_link_title_text'] . " " . $title;
    if ($options['glyph_after_link_title']) $title = $title . " " . $options['glyph_after_link_title_text'];
  }

  return $title;
}
add_filter('the_title_rss', 'insert_title_glyph_rss');


/*-----------------------------------------------------------------------------
  Options menu functions
-----------------------------------------------------------------------------*/

// Add the menu 
function dfll_menu() {
  add_options_page('DF-Style Linked List Options', 'DF-Style Linked List', 'manage_options', 'dfll', 'dfll_options_page');
}
add_action('admin_menu', 'dfll_menu');

// Initialise the settings
function dfll_init() {
  register_setting("dfll_options", "dfll_options");
  add_settings_section("dfll_main", "Linked List Properties", "dfll_text", "dfll");
  add_settings_field("link_goes_to", "RSS link goes to linked item", "link_goes_to_callback", "dfll", "dfll_main");
  add_settings_field("glyph_after_post", "Insert permalink after post", "glyph_after_post_callback", "dfll", "dfll_main");
  add_settings_field("glyph_after_post_text", "", "glyph_after_post_text_callback", "dfll", "dfll_main");
  add_settings_field("glyph_before_link_title", "Highlight link post titles", "glyph_before_link_title_callback", "dfll", "dfll_main");
  add_settings_field("glyph_before_link_title_text", "", "glyph_before_link_title_text_callback", "dfll", "dfll_main");
  add_settings_field("glyph_after_link_title", "", "glyph_after_link_title_callback", "dfll", "dfll_main");
  add_settings_field("glyph_after_link_title_text", "", "glyph_after_link_title_text_callback", "dfll", "dfll_main");
  add_settings_section("dfll_main2", "Blog Post Properties", "dfll_text2", "dfll");
  add_settings_field("glyph_before_blog_title", "Highlight blog post titles", "glyph_before_blog_title_callback", "dfll", "dfll_main2");
  add_settings_field("glyph_before_blog_title_text", "", "glyph_before_blog_title_text_callback", "dfll", "dfll_main2");
  
}
add_action('admin_init', 'dfll_init');


/* Callback functions to display each of the options */

function link_goes_to_callback() {
  $options = get_option('dfll_options');
  if($options['link_goes_to']) { $checked = ' checked="checked" '; }
  echo "<input " . $checked . " name='dfll_options[link_goes_to]' type='checkbox' />";
  echo " Linked list entries point to the linked item in question, i.e. when you click on the link title in your RSS reader, your browser opens that link instead of your blog permalink.";
}

function glyph_after_post_callback() {
  $options = get_option('dfll_options');
  if($options['glyph_after_post']) { $checked = ' checked="checked" '; }
  echo "<input " . $checked . " name='dfll_options[glyph_after_post]' type='checkbox' />";
  echo " At the bottom of each linked list blog post, show a permalink bringing you back to your blog post. On DF, this is ★. <em>Note for theme customizers</em>: this is what's returned in get_glyph() (just the text) and get_the_permalink_glyph() (text wrapped inside an anchor).";
}

function glyph_after_post_text_callback() { 
  $options = get_option('dfll_options');
  echo "<label for='input1'>Text for permalink: </label>";
  echo "<input name='dfll_options[glyph_after_post_text]' size='12' type='text' value='{$options['glyph_after_post_text']}' id='input1' /> <span class='eg'>e.g. ★ or Permalink. ";
  if (!$options['glyph_after_post']) echo "Remember to check the checkbox above.";
  echo "</span>";
}

function glyph_before_link_title_callback() {
  $options = get_option('dfll_options');
  if($options['glyph_before_link_title']) { $checked = ' checked="checked" '; }
  echo "<input " . $checked . " name='dfll_options[glyph_before_link_title]' type='checkbox' />";
  echo " Show text <em>before</em> linked-list article titles, e.g. <em>Link: </em>. This is useful if you want to distinguish these link posts from your regular blog posts, and may help readers figure out how to get to the link.";
}

function glyph_before_link_title_text_callback() { 
  $options = get_option('dfll_options');
  echo "<label for='input2'>Text to display: </label>";
  echo "<input {$style} name='dfll_options[glyph_before_link_title_text]' size='12' type='text' value='{$options['glyph_before_link_title_text']}' id='input2' /> <span class='eg'>e.g. Link:. ";
  if (!$options['glyph_before_link_title']) echo "Remember to check the checkbox above.";
  echo "</span>";
}

function glyph_after_link_title_callback() {
  $options = get_option('dfll_options');
  if($options['glyph_after_link_title']) { $checked = ' checked="checked" '; }
  echo "<input " . $checked . " name='dfll_options[glyph_after_link_title]' type='checkbox' />";
  echo " Show text <em>after</em> linked-list article titles, e.g. <em>&raquo;</em>. This is useful if you want to distinguish these link posts from your regular blog posts, and may help readers figure out how to get to the link.";
}

function glyph_after_link_title_text_callback() { 
  $options = get_option('dfll_options');
  echo "<label for='input3'>Text to display: </label>";
  echo "<input {$style} name='dfll_options[glyph_after_link_title_text]' size='12' type='text' value='{$options['glyph_after_link_title_text']}' id='input3' /> <span class='eg'>e.g. &raquo;. ";
  if (!$options['glyph_after_link_title']) echo "Remember to check the checkbox above.";
  echo "</span>";  
}

function glyph_before_blog_title_callback() {
  $options = get_option('dfll_options');
  if($options['glyph_before_blog_title']) { $checked = ' checked="checked" '; }
  echo "<input " . $checked . " name='dfll_options[glyph_before_blog_title]' type='checkbox' />";
  echo " Show text before blog article titles in the RSS feed. This helps distinguish them from link posts, which is useful if you link more than you post. DF has a ★ in front of such articles.";
}

function glyph_before_blog_title_text_callback() {
  $options = get_option('dfll_options');
  echo "<label for='input4'>Text to display: </label>";
  echo "<input {$style} name='dfll_options[glyph_before_blog_title_text]' size='12' type='text' value='{$options['glyph_before_blog_title_text']}' id='input4' /> <span class='eg'>e.g. ★. ";
  if (!$options['glyph_before_blog_title']) echo "Remember to check the checkbox above.";
  echo "</span>";  
}

/* Callback functions for main sections */

function dfll_text() {
  echo "<p>This section defines the behaviour of RSS entries of linked list posts. Default behaviour follows Daring Fireball.</p>";
}

function dfll_text2() {
  echo "<p>This section defines the behaviour of RSS entries of blog posts (i.e., not links).</p>";  
}

/* Add default options */

register_activation_hook(__FILE__, 'dfll_defaults_callback');
// Define default option settings
function dfll_defaults_callback() {
  $arr = array("link_goes_to"=>"true", 
               "glyph_after_post" => "true", 
               "glyph_after_post_text" => "★", 
               "glyph_before_link_title" => "", 
               "glyph_before_link_title_text" => "", 
               "glyph_after_link_title" => "", 
               "glyph_after_link_title_text" => "", 
               "glyph_before_blog_title" => "true", 
               "glyph_before_blog_title_text" => "★"
               );
  update_option('dfll_options', $arr);
}

/* Actual options page rendering */

function dfll_options_page() {

  ?>
  
  <style type="text/css" media="screen">
   .eg { color: #888; }
   .append { color: #d66; }
  </style>
  
  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Daring Fireball-Style Linked List Plugin Settings</h2>

    <div style="border:1px solid #aaa;margin:2em 0 1em;background-color:#eee;padding:0 1em 1em;">
      <h3>Notes - Read First!</h3>
      <ul style="margin-left: 1.5em; list-style-type:disc;">
  	  <li>Changing the settings on this page <em>only affects the behaviour of your RSS feeds</em>, i.e. it won't change the way your blog is displayed on the web. To change your blog's display properties, edit your theme to use the following functions: is_linked_list(), get_the_linked_list_link(), get_glyph() and get_the_permalink_glyph().</li>
      <li>To enable linked list post behaviour, make sure you create a custom field called <strong>linked_list_url</strong> containing the link you want your post to go to. Other posts without this custom field will be treated as blog, or "regular", posts. If you don't know what custom fields are or how to set them, read the first few sections of <a href="http://www.rlmseo.com/blog/wordpress-custom-fields/">this article</a>.</li>
      <li>Some glyphs (symbols) you can use: &#9733; &#8594; &#8658; &nabla; &loz; &#10004; &#10010; &#10020; &#10022; &#9819; &#9820; &raquo; &laquo; (<a href="http://www.danshort.com/HTMLentities/index.php">more here</a>). You can just copy and paste these into the fields below.</li>
      </ul>
    </div>

    <form name="df-form" method="post" action="options.php">
      <?php settings_fields('dfll_options'); ?>
      <?php do_settings_sections('dfll'); ?>
      <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>      
    </form>
  
  </div>

  <?php

}

?>

<?php
/*
 * @package Twenty_Fifteen_YGAA
 * @since Twenty Fifteen YGAA 1.0
 */

// Add parent scripts and styles
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

//
// Disable support for some Post Formats.
//
add_action('after_setup_theme', 'uoy_remove_post_formats', 11);
function uoy_remove_post_formats() {
  remove_theme_support('post-formats');
  add_theme_support('post-formats', array(
    'image', 'video', 'gallery'
));
}

//
// Remove Yoast SEO boxes from contributors
//
function uoy_remove_yoast(){
  if(!current_user_can('edit_others_pages')) {
    // Remove page analysis columns from post lists, also SEO status on post editor
    add_filter('wpseo_use_page_analysis', '__return_false');
    // Remove Yoast meta boxes
    add_action('add_meta_boxes', 'disable_seo_metabox', 100000);
  }
}
add_action('init', 'uoy_remove_yoast');

function disable_seo_metabox(){
    remove_meta_box('wpseo_meta', 'post', 'normal');
    remove_meta_box('wpseo_meta', 'page', 'normal');
}
// User profile shortcode
// Use: [user id=1] [user email="chris.marsh@york.ac.uk"] [user name="Chris Marsh"] [user slug="chrismarsh"]
// If more than one attribute is sent through, it will search by ID first, then email, name and slug
function uoy_display_user($atts) {
  extract(shortcode_atts(array(
    "id" => false,
    "email" => false,
    "name" => false,
    "slug" => false
), $atts));
  $field = false;
  $value = false;
  if ($id !== false) {
    $field = 'id';
    $value = $id;
  } elseif ($email !== false) {
    $field = 'email';
    $value = $email;
  } elseif ($name !== false) {
    $field = 'login';
    $value = $name;
  } elseif ($slug !== false) {
    $field = 'slug';
    $value = $slug;
  }
  $user = get_user_by($field, $value);
  if ($user === false) {
    return '<p>User was not found</p>';
  }
  $img = get_avatar($user->ID);
  $bio = get_the_author_meta('description', $user->ID);
  $url = esc_url(get_author_posts_url($user->ID));
  //$twitter = get_the_author_meta('twitter', $user->ID);

  $r = '<div class="user-profile">'."\n";
  $r.= '  <div class="user-profile__image">'.$img.'</div>'."\n";
  $r.= '  <div class="user-profile__meta">'."\n";
  $r.= '    <h4><a href="'.$url.'">'.$user->display_name.'</a></h4>'."\n";
  if ($bio != '') {
    $r.= '    <p>'.$bio.'</p>'."\n";
  }
  $r.= '  </div>'."\n";
  $r.= '</div>'."\n";
  return $r;
}
add_shortcode('user', 'uoy_display_user');

//
// Add option to customizer for light and dark images
//
function uoy_customize_register($wp_customize) {

  $wp_customize->add_setting('logo_color_setting', array(
    'type' => 'theme_mod',
    'capability' => 'edit_theme_options',
    'default' => 'dark',
    'transport' => 'refresh'
));

  $wp_customize->add_control('logo_color', array(
    'label' => __('Logo colour'),
    'type' => 'radio',
    'section' => 'header_image',
    'settings' => 'logo_color_setting',
    'choices' => array('dark' => 'Dark', 'light' => 'Light')
));
}
add_action('customize_register', 'uoy_customize_register', 12);
//
// Add user capabilties
//
function uoy_add_theme_caps() {
    // gets the contributor role
    $role = get_role('contributor');
    $role->add_cap('upload_files');
}
add_action('admin_init', 'uoy_add_theme_caps');

//
// Add Typekit fonts
//
add_action('wp_head', 'uoy_typekit');
function uoy_typekit() {
?>
  <!-- Typekit script -->
  <script src="//use.typekit.net/dvj8rpp.js"></script>
  <script>try{Typekit.load();}catch(e){}</script>
<?php
}
//
// Overwrite google fonts
//
function twentyfifteen_fonts_url() {}
//
// Add User ID column to User list
//
add_filter('manage_users_columns', 'uoy_add_user_id_column');
function uoy_add_user_id_column($columns) {
  $columns['user_id'] = 'User ID';
  return $columns;
}
add_action('manage_users_custom_column',  'uoy_show_user_id_column_content', 10, 3);
function uoy_show_user_id_column_content($value, $column_name, $user_id) {
  $user = get_userdata($user_id);
  if ('user_id' == $column_name) {
    return $user_id;
  }
  return $value;
}
//
// Allow admins of sites to edit users
//
function uoy_admin_users_caps($caps, $cap, $user_id, $args) {
  foreach ($caps as $key => $capability) {
    if ($capability != 'do_not_allow') {
      continue;
    }
    switch ($cap) {
      case 'edit_user':
      case 'edit_users':
        $caps[$key] = 'edit_users';
        break;
      case 'delete_user':
      case 'delete_users':
        $caps[$key] = 'delete_users';
        break;
      case 'create_users':
        $caps[$key] = $cap;
        break;
    }
  }
  return $caps;
}
add_filter('map_meta_cap', 'uoy_admin_users_caps', 1, 4);
remove_all_filters('enable_edit_any_user_configuration');
add_filter('enable_edit_any_user_configuration', '__return_true');

//
// Checks that both the editing user and the user being edited are
// members of the blog and prevents the super admin being edited.
//
function uoy_edit_permission_check() {
  global $current_user, $profileuser;
  $screen = get_current_screen();
  get_currentuserinfo();
  if (!is_super_admin($current_user->ID) && in_array($screen->base, array('user-edit', 'user-edit-network'))) {
    // editing a user profile
    if (is_super_admin($profileuser->ID)) {
      // trying to edit a superadmin while less than a superadmin
      wp_die(__('You do not have permission to edit this user.'));
    } elseif (!(is_user_member_of_blog($profileuser->ID, get_current_blog_id()) && is_user_member_of_blog($current_user->ID, get_current_blog_id()))) {
      // editing user and edited user aren't members of the same blog
      wp_die(__('You do not have permission to edit this user.'));
    }
  }
}
add_filter('admin_head', 'uoy_edit_permission_check', 1, 4);
//
// Redirect front page to /student-voices
// Remove when aggregated blog is needed
//
// add_action('template_redirect', 'uoy_redirect_ms_front_page');
function uoy_redirect_ms_front_page() {
  if (is_main_site() && is_front_page()) {
	  $url = home_url('/student-voices/');
	  // 302 is a temporary redirect
    wp_redirect($url, 302);
    exit;
  }
}
if (! function_exists('twentyfifteen_entry_meta')) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Twenty Fifteen UoY 1.0
 */
function twentyfifteen_uoy_entry_meta() {
  if (is_sticky() && is_home() && ! is_paged()) {
    printf('<span class="sticky-post">%s</span>', __('Featured', 'twentyfifteen'));
  }

  $format = get_post_format();
  if (current_theme_supports('post-formats', $format)) {
    printf('<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
      sprintf('<span class="screen-reader-text">%s </span>', _x('Format', 'Used before post format.', 'twentyfifteen')),
      esc_url(get_post_format_link($format)),
      get_post_format_string($format)
   );
  }

  if ('post' == get_post_type()) {

    $categories_list = get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen'));
    if ($categories_list && twentyfifteen_categorized_blog()) {
      printf('<span class="cat-links">%1$s %2$s</span>',
        _x('Posted in', 'Used before category names.', 'twentyfifteen'),
        $categories_list
     );
    }

    $tags_list = get_the_tag_list('', _x(', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen'));
    if ($tags_list) {
      printf('<span class="tags-links">%1$s %2$s</span>',
        _x('Tagged with', 'Used before tag names.', 'twentyfifteen'),
        $tags_list
     );
    }
  }

  if (is_attachment() && wp_attachment_is_image()) {
    // Retrieve attachment metadata.
    $metadata = wp_get_attachment_metadata();

    printf('<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
      _x('Full size', 'Used before full size attachment link.', 'twentyfifteen'),
      esc_url(wp_get_attachment_url()),
      $metadata['width'],
      $metadata['height']
   );
  }

  if (! is_single() && ! post_password_required() && (comments_open() || get_comments_number())) {
    echo '<span class="comments-link">';
    /* translators: %s: post title */
    comments_popup_link(sprintf(__('Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentyfifteen'), get_the_title()));
    echo '</span>';
  }
}
endif;

/*
// Add custom fields to the API
add_action('rest_api_init','uoy_custom_api_fields_init',19);
function uoy_custom_api_fields_init() {
  // Add Google profile pic to users
  register_rest_field('user', 'profile_pic_url', array('get_callback' => 'uoy_custom_api_fields_user_pic'));
  // Add custom fields
  register_rest_field('post', 'custom_fields', array('get_callback' => 'uoy_custom_api_fields_custom_fields'));
}
function uoy_custom_api_fields_user_pic ($object, $field_name, $request){
  //$googlepic = get_user_meta($object['id'])['gpa_user_avatar'][0];
  $avatarpic = get_wp_user_avatar_src($object['id'], 96);
  return is_null($avatarpic) ? get_avatar_url($object['id']) : $avatarpic;
  //return get_avatar_url($object['id']);
}
function uoy_custom_api_fields_custom_fields ($object, $field_name, $request){
  return get_post_meta($object['id']);
}
*/

/*
 * Change the email address that sends from our blog
 */
add_filter('wp_mail_from', 'my_mail_from');
function my_mail_from($email) {
    return "do-not-reply@york.ac.uk";
}
add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name ) {
    return "University of York";
}

// Removes the hash location from "read more" links
function remove_read_more_hash( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'remove_read_more_hash' );

/*
 * Update a user's image if their Google login isn't valid any more
 * INVESTIGATION, NOT FOLLOWED UP - CM 4/10/16
 *
add_filter('get_avatar', 'check_avatar', 12, 5);

function check_avatar($avatar, $id_or_email, $size, $default, $alt) {

  // Get user ID
  if (is_object($id_or_email)) {
    // Comment or post
    $id_or_email = (int) $id_or_email->user_id;
  } else if (is_string($id_or_email)) {
    // Try email
    $user = get_user_by('email',$id_or_email);
    if ($user) {
      $id_or_email = $user->ID;
    }
  }

  //echo '<p>Checking avatar ('.$id_or_email.') now!</p>';

  if (is_numeric($id_or_email)) {
    $google_picture = get_user_meta($id_or_email, 'gpa_user_avatar', true);
    $user_meta = get_user_meta($id_or_email);
    // echo '<pre>';
    // print_r($user_meta);
    // echo '</pre>';
    if ($google_picture) {
      $safe_alt = false === $alt ? '' : esc_attr( $alt );
      $avatar = "<img alt='{$safe_alt}' src='{$google_picture}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
    }
  }

  return $avatar;

}
*/
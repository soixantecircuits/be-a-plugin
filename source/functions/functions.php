<?php

add_action( 'wp_enqueue_scripts', 'be_a_plugin_enqueue_scripts' );

if ( ! function_exists( 'be_a_plugin_enqueue_scripts' ) ) :

/**
* Add theme styles and scripts here
*/
function be_a_plugin_enqueue_scripts() {

	if ( ! is_admin() ) {
		wp_enqueue_style(
			'be_a_plugin-style',
			get_bloginfo( 'stylesheet_url' )
		);
	}

}

endif; // be_a_plugin_enqueue_scripts

add_action( 'after_setup_theme', 'be_a_plugin_setup' );

if ( ! function_exists( 'be_a_plugin_setup' ) ) :

function codex_custom_init() {
  $labels = array(
    'name' => _x('Plugins', 'post type general name'),
    'singular_name' => _x('Plugin', 'post type singular name'),
    'add_new' => _x('Add New', 'book'),
    'add_new_item' => __('Add New Plugin'),
    'edit_item' => __('Edit Plugin'),
    'new_item' => __('New Plugin'),
    'all_items' => __('All Plugins'),
    'view_item' => __('View Plugin'),
    'search_items' => __('Search Plugins'),
    'not_found' =>  __('No plugin found'),
    'not_found_in_trash' => __('No plugins found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => __('Plugins')

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  ); 
  register_post_type('plugin',$args);
}

//add filter to ensure the text Book, or book, is displayed when user updates a book 

function codex_plugin_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['plugin'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Plugin updated. <a href="%s">View plugin</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Plugin updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Plugin restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Plugin published. <a href="%s">View plugin</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Plugin saved.'),
    8 => sprintf( __('Plugin submitted. <a target="_blank" href="%s">Preview plugin</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Plugin scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview plugin</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Plugin draft updated. <a target="_blank" href="%s">Preview plugin</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

function register_my_menus() {
  register_nav_menus(
    array( 'footer-menu' => __( 'footer Menu' ) )
  );
}

//display contextual help for Books
function codex_add_help_plugin_text( $contextual_help, $screen_id, $screen ) { 
  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'plugin' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a plugin:') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct tags such as Mystery, or Historic.') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the plugin review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this plugin, then click on Ok.') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>' ;
  } elseif ( 'edit-plugin' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of plugins...') . '</p>' ;
  }
  return $contextual_help;
}

// Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
function wpfme_remove_img_ptags($content){
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}


// Call Googles HTML5 Shim, but only for users on old versions of IE
function wpfme_IEhtml5_shim () {
  global $is_IE;
  if ($is_IE)
  echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
}


// Obscure login screen error messages
function wpfme_login_obscure(){ return '<strong>Sorry</strong>: Think you have gone wrong somwhere!';}

 
/**
* Returns Lorem Ipsum text for blank pages
* 
* @param string $content - the page's current contents
* @return string
*/
function emw_custom_filter_the_content ($content) {
    if ($content == '') {
        if ($c = get_transient ('lipsum'))
            return $c;
        $content = wp_remote_get ('http://www.lipsum.com/feed/json');
        if (!is_wp_error($content)) {
            $content = json_decode (str_replace ("\n", '</p><p>', $content['body']));
            $content = '<p>'.$content->feed->lipsum.'</p>';
            set_transient ('lipsum', $content, 3600); // Cache the text for one hour
            return $content;
        }
    } else
        return $content;
}

/**
* Set up your theme here
*/
function be_a_plugin_setup() {
	add_theme_support( 'post-thumbnails' );
  add_action( 'init', 'codex_custom_init' );
  add_filter( 'post_updated_messages', 'codex_plugin_updated_messages' );
  add_action( 'contextual_help', 'codex_add_help_plugin_text', 10, 3 );
  add_filter( 'login_errors', 'wpfme_login_obscure' );
  add_action('wp_head', 'wpfme_IEhtml5_shim');
  add_filter('the_content', 'wpfme_remove_img_ptags');
  add_filter ('the_content', 'emw_custom_filter_the_content');
  add_action( 'init', 'register_my_menus' );

  if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'id' => 'footer-menus',
        'name' => 'Footer Menus',
        'before_widget' => '<div class="column %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3><br>'
    ));
  }
}

class Walker_Nav_Menu_Rdc extends Walker_Nav_Menu{
    function start_el(&$output, $item, $depth, $args)
      {
           global $wp_query, $rubrique;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           $prepend = '<strong>';
           $append = '</strong>';
           $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

           $id = $item->object_id;

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= $description.$args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}

endif; // be_a_plugin_setup

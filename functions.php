<?php
//required
//max width of youtube and other embeds
if ( ! isset( $content_width ) ) $content_width = 700;

//activate sleeping features of WP

//post formats allow different styles on different kinds of posts 
//use post_class() on your post container
//Only activate the post-formats you want to use
//add_theme_support( 'post-formats', array( 'image', 'link', 'gallery', 'audio', 'video', 'aside', 'chat', 'quote' , 'status' ) );

//featured images
add_theme_support( 'post-thumbnails' );
			  //name, width, height, crop?
add_image_size( 'teeny', 20, 20, true );

//custom background on body tag
add_theme_support( 'custom-background' );

//custom header
$header_args = array(
	'width' 	=> 1000,
	'height'	=> 300,
);

add_theme_support( 'custom-header', $header_args );

//custom logo
$logo_args = array(
	'width' 		=> 300,
	'height'		=> 300,
	'flex-width'	=> true,
	'flex-height' 	=> true,
);
add_theme_support( 'custom-logo', $logo_args );


//improve RSS and JSON feeds
add_theme_support( 'automatic-feed-links' );

//use modern markup on wordpress HTML output
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 
									'gallery', 'caption' ) );


//remove the <title> HTML from header.php. (WordPress will automatically add it)
add_theme_support( 'title-tag' );


//customize the length of all excerpts (WordPress default is 55 words)
function var_excerpt_length(){
	return 10;
}
add_filter( 'excerpt_length', 'var_excerpt_length' );

//change the [...]
function var_dotdotdot(){
	return '&hellip; <a href="' . get_permalink() . '" class="read-more">Read More</a>' ;
}
add_filter( 'excerpt_more', 'var_dotdotdot' );


//action hook experiment
function action_hook_exp(){
	echo 'The loop has started.';
}
//add_action( 'loop_start', 'action_hook_exp', 1 );


/**
 * All Menu Locations for this theme
 */
function var_menu_areas(){
	register_nav_menus( array(
		//code-friendly => human-friendly
		'main_menu' 	=> 'Main Menu',
		'social_menu' 	=> 'Social Media Menu',
	) );
}
add_action( 'init', 'var_menu_areas' );

//HTML output for Social Media Menu. Call this in header.php
function var_fancy_social_menu(){
	//Social Media Links
		 wp_nav_menu( array(
		 	'theme_location' 	=> 'social_menu',
		 	'container' 		=> false, //no container
		 	'menu_class'		=> 'social-navigation',
		 	'fallback_cb'		=> false, //do nothing if no menu in this location
		 	'link_before'		=> '<span class="screen-reader-text">',
		 	'link_after'		=> '</span>',
		 ) );
}

/**
 * Load all CSS and JS this theme needs
 */
add_action( 'wp_enqueue_scripts', 'var_stylesheets' );
function var_stylesheets(){
	wp_enqueue_style( 'genericons', get_stylesheet_directory_uri() . '/genericons/genericons.css' );

	if ( is_singular() ) wp_enqueue_script( "comment-reply" );
}


/**
 * Pagination for archives and singular things
 */
function var_pagination(){
	//if we are looking at a single post, show the single pagination
	if( is_singular() ){
		previous_post_link();
		next_post_link();
	}
	//otherwise show the numbered pagination (archive view)
	else{
		//numbered pagination
		the_posts_pagination();

		//next/previous buttons
		//next_posts_link();
		//previous_posts_link();
	}
}

/**
 * Widget Areas (Dynamic Sidebars)
 */
function var_widget_areas(){
	//register one widget area
	register_sidebar( array(
		'name'          => 'Blog Sidebar',
		'id'            => 'blog-sidebar',   
		'description'   => 'Appears next to blog posts and archives',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => 'Page Sidebar',
		'id'            => 'page-sidebar',   
		'description'   => 'Appears next to static pages',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => 'Footer Widgets',
		'id'            => 'footer-widgets',   
		'description'   => 'Appears at the bottom of every screen',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'var_widget_areas' );

/**
 * Count the Comments and trackbacks separately
 */
add_filter( 'get_comments_number', 'var_comment_count' );
function var_comment_count(){
	//post id
	global $id;
	$comments = get_approved_comments($id);

	$count = 0;
	foreach( $comments as $comment ){
		//if it's a real comment, increase the count
		if( $comment->comment_type == '' ){
			$count ++;
		}
	}
	return $count;
}

//count trackbacks and pingbacks
function var_pings_count(){
	//post id
	global $id;
	$comments = get_approved_comments($id);

	$count = 0;
	foreach( $comments as $comment ){
		//if it's not real comment, increase the count
		if( $comment->comment_type != '' ){
			$count ++;
		}
	}
	return $count;
}




//no close php

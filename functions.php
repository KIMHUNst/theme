<?php
/**
 * Clean Approval Blog functions and definitions.
 *
 * @package Clean_Approval_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cab_setup() {
    load_theme_textdomain( 'clean-approval-blog', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'clean-approval-blog' ),
    ) );
}
add_action( 'after_setup_theme', 'cab_setup' );

function cab_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'cab_content_width', 760 );
}
add_action( 'after_setup_theme', 'cab_content_width', 0 );

function cab_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'clean-approval-blog' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here. Good place for categories, recent posts, and AdSense blocks.', 'clean-approval-blog' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'cab_widgets_init' );

function cab_scripts() {
    wp_enqueue_style( 'clean-approval-blog-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'cab_scripts' );

function cab_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    echo '<span class="posted-on">' . wp_kses_post( $time_string ) . '</span>';
}

function cab_categories() {
    $categories = get_the_category_list( esc_html__( ', ', 'clean-approval-blog' ) );
    if ( $categories ) {
        echo '<span class="cat-links"> · ' . wp_kses_post( $categories ) . '</span>';
    }
}

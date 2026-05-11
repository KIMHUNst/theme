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

    add_theme_support( 'custom-line-height' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'clean-approval-blog' ),
        'footer'  => esc_html__( 'Footer Menu', 'clean-approval-blog' ),
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
    wp_enqueue_script( 'clean-approval-blog-script', get_template_directory_uri() . '/assets/js/theme.js', array(), wp_get_theme()->get( 'Version' ), true );
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

function cab_schema_markup() {
    if ( is_single() ) {
        echo '<meta itemprop="author" content="' . esc_attr( get_the_author() ) . '">';
        echo '<meta itemprop="datePublished" content="' . esc_attr( get_the_date( DATE_W3C ) ) . '">';
    }
}
add_action( 'wp_head', 'cab_schema_markup' );

function cab_open_graph_tags() {
    if ( is_admin() ) {
        return;
    }

    $title       = is_singular() ? get_the_title() : get_bloginfo( 'name' );
    $description = is_singular() ? wp_strip_all_tags( get_the_excerpt() ) : get_bloginfo( 'description' );
    $url         = is_singular() ? get_permalink() : home_url( '/' );
    $image       = '';

    if ( is_singular() && has_post_thumbnail() ) {
        $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    }

    echo '<meta property="og:type" content="' . esc_attr( is_singular() ? 'article' : 'website' ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";

    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'cab_open_graph_tags', 5 );

function cab_auto_toc( $content ) {
    if ( ! is_single() || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }

    if ( false === strpos( $content, '<h2' ) ) {
        return $content;
    }

    preg_match_all( '/<h2([^>]*)>(.*?)<\/h2>/i', $content, $matches, PREG_SET_ORDER );

    if ( count( $matches ) < 2 ) {
        return $content;
    }

    $toc = '<nav class="cab-toc"><strong>' . esc_html__( 'Table of Contents', 'clean-approval-blog' ) . '</strong><ol>';

    foreach ( $matches as $index => $match ) {
        $heading_text = wp_strip_all_tags( $match[2] );
        $anchor       = 'section-' . ( $index + 1 );
        $replacement  = '<h2 id="' . esc_attr( $anchor ) . '"' . $match[1] . '>' . $match[2] . '</h2>';
        $content      = str_replace( $match[0], $replacement, $content );
        $toc         .= '<li><a href="#' . esc_attr( $anchor ) . '">' . esc_html( $heading_text ) . '</a></li>';
    }

    $toc .= '</ol></nav>';

    return $toc . $content;
}
add_filter( 'the_content', 'cab_auto_toc' );

function cab_track_post_views() {
    if ( ! is_single() || is_user_logged_in() ) {
        return;
    }

    $post_id = get_the_ID();
    $views   = (int) get_post_meta( $post_id, '_cab_post_views', true );

    update_post_meta( $post_id, '_cab_post_views', $views + 1 );
}
add_action( 'wp_head', 'cab_track_post_views' );

function cab_get_post_views( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $views   = (int) get_post_meta( $post_id, '_cab_post_views', true );

    return number_format_i18n( $views );
}

function cab_popular_posts_list( $limit = 5 ) {
    $popular = new WP_Query( array(
        'posts_per_page'      => absint( $limit ),
        'meta_key'            => '_cab_post_views',
        'orderby'             => 'meta_value_num',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    ) );

    if ( ! $popular->have_posts() ) {
        return;
    }

    echo '<ol class="cab-popular-posts">';
    while ( $popular->have_posts() ) {
        $popular->the_post();
        echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a><span>' . esc_html( cab_get_post_views() ) . ' views</span></li>';
    }
    echo '</ol>';

    wp_reset_postdata();
}

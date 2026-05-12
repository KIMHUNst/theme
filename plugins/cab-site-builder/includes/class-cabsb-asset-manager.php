<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Asset_Manager {

    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'optimize_assets' ), 999 );
        add_filter( 'style_loader_tag', array( __CLASS__, 'lazy_styles' ), 10, 4 );
    }

    public static function optimize_assets() {
        if ( ! is_admin() ) {
            wp_dequeue_style( 'wp-block-library-theme' );
        }
    }

    public static function lazy_styles( $html, $handle, $href, $media ) {
        $excluded = array(
            'dashicons',
            'admin-bar',
        );

        if ( in_array( $handle, $excluded, true ) ) {
            return $html;
        }

        return str_replace(
            "rel='stylesheet'",
            "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"",
            $html
        );
    }
}

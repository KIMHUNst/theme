<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Hooks {

    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'before_header_hook' ) );
        add_action( 'wp_footer', array( __CLASS__, 'footer_hook' ) );
    }

    public static function before_header_hook() {
        $content = get_option( 'cabsb_hook_before_header', '' );

        if ( $content ) {
            echo '<div class="cabsb-hook cabsb-before-header">' . wp_kses_post( $content ) . '</div>';
        }
    }

    public static function footer_hook() {
        $content = get_option( 'cabsb_hook_footer', '' );

        if ( $content ) {
            echo '<div class="cabsb-hook cabsb-footer-hook">' . wp_kses_post( $content ) . '</div>';
        }
    }
}

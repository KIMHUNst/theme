<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Visibility {

    public static function init() {
        add_filter( 'body_class', array( __CLASS__, 'visibility_classes' ) );
    }

    public static function visibility_classes( $classes ) {
        if ( wp_is_mobile() ) {
            $classes[] = 'cabsb-mobile-device';
        } else {
            $classes[] = 'cabsb-desktop-device';
        }

        if ( is_user_logged_in() ) {
            $classes[] = 'cabsb-user-logged-in';
        }

        return $classes;
    }

    public static function should_display( $rule ) {
        switch ( $rule ) {
            case 'logged_in':
                return is_user_logged_in();

            case 'logged_out':
                return ! is_user_logged_in();

            case 'mobile':
                return wp_is_mobile();

            case 'desktop':
                return ! wp_is_mobile();
        }

        return true;
    }
}

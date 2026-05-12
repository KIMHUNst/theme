<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Performance {

    public static function init() {
        add_filter( 'script_loader_tag', array( __CLASS__, 'defer_scripts' ), 10, 2 );
        add_action( 'wp_head', array( __CLASS__, 'resource_hints' ), 1 );
        add_action( 'send_headers', array( __CLASS__, 'headers' ) );
    }

    public static function defer_scripts( $tag, $handle ) {
        $exclude = array( 'jquery-core', 'jquery-migrate' );

        if ( in_array( $handle, $exclude, true ) ) {
            return $tag;
        }

        if ( false === strpos( $tag, ' defer ' ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }

        return $tag;
    }

    public static function resource_hints() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    }

    public static function headers() {
        if ( headers_sent() ) {
            return;
        }

        header( 'X-DNS-Prefetch-Control: on' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        header( 'Permissions-Policy: interest-cohort=()' );
    }
}

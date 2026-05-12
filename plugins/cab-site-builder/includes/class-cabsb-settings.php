<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Settings {

    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    public static function register_settings() {
        register_setting( 'cabsb_settings_group', 'cabsb_container_width', array( 'sanitize_callback' => 'absint' ) );
        register_setting( 'cabsb_settings_group', 'cabsb_body_font_size', array( 'sanitize_callback' => 'absint' ) );
        register_setting( 'cabsb_settings_group', 'cabsb_enable_sticky_header', array( 'sanitize_callback' => 'rest_sanitize_boolean' ) );
        register_setting( 'cabsb_settings_group', 'cabsb_enable_back_to_top', array( 'sanitize_callback' => 'rest_sanitize_boolean' ) );
    }
}

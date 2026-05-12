<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Import_Export {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'handle_export' ) );
        add_action( 'admin_init', array( __CLASS__, 'handle_import' ) );
    }

    public static function menu() {
        add_submenu_page(
            'cab-site-builder',
            __( 'Import / Export', 'cab-site-builder' ),
            __( 'Import / Export', 'cab-site-builder' ),
            'manage_options',
            'cabsb-import-export',
            array( __CLASS__, 'page' )
        );
    }

    public static function page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CAB Import / Export', 'cab-site-builder' ); ?></h1>
            <p><?php esc_html_e( 'Move builder settings between sites.', 'cab-site-builder' ); ?></p>

            <h2><?php esc_html_e( 'Export Settings', 'cab-site-builder' ); ?></h2>
            <form method="post">
                <?php wp_nonce_field( 'cabsb_export_settings', 'cabsb_export_nonce' ); ?>
                <?php submit_button( __( 'Download Export File', 'cab-site-builder' ), 'primary', 'cabsb_export' ); ?>
            </form>

            <hr>

            <h2><?php esc_html_e( 'Import Settings', 'cab-site-builder' ); ?></h2>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field( 'cabsb_import_settings', 'cabsb_import_nonce' ); ?>
                <input type="file" name="cabsb_import_file" accept="application/json">
                <?php submit_button( __( 'Import Settings', 'cab-site-builder' ), 'secondary', 'cabsb_import' ); ?>
            </form>
        </div>
        <?php
    }

    public static function handle_export() {
        if ( empty( $_POST['cabsb_export'] ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) || empty( $_POST['cabsb_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cabsb_export_nonce'] ) ), 'cabsb_export_settings' ) ) {
            return;
        }

        $data = array(
            'theme_mods' => array(
                'cabsb_container_width'       => get_theme_mod( 'cabsb_container_width', 1200 ),
                'cabsb_content_spacing'       => get_theme_mod( 'cabsb_content_spacing', 32 ),
                'cabsb_accent_color'          => get_theme_mod( 'cabsb_accent_color', '#2563eb' ),
                'cabsb_body_font_size'        => get_theme_mod( 'cabsb_body_font_size', 17 ),
                'cabsb_header_height'         => get_theme_mod( 'cabsb_header_height', 80 ),
                'cabsb_enable_sticky_header'  => get_theme_mod( 'cabsb_enable_sticky_header', true ),
                'cabsb_footer_columns'        => get_theme_mod( 'cabsb_footer_columns', 4 ),
                'cabsb_enable_popup'          => get_theme_mod( 'cabsb_enable_popup', false ),
                'cabsb_popup_content'         => get_theme_mod( 'cabsb_popup_content', '' ),
                'cabsb_enable_sticky_cta'     => get_theme_mod( 'cabsb_enable_sticky_cta', true ),
            ),
            'options' => array(
                'cabsb_hook_before_header' => get_option( 'cabsb_hook_before_header', '' ),
                'cabsb_hook_footer'        => get_option( 'cabsb_hook_footer', '' ),
            ),
        );

        header( 'Content-Type: application/json' );
        header( 'Content-Disposition: attachment; filename="cab-site-builder-export.json"' );
        echo wp_json_encode( $data );
        exit;
    }

    public static function handle_import() {
        if ( empty( $_POST['cabsb_import'] ) || empty( $_FILES['cabsb_import_file']['tmp_name'] ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) || empty( $_POST['cabsb_import_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cabsb_import_nonce'] ) ), 'cabsb_import_settings' ) ) {
            return;
        }

        $json = file_get_contents( sanitize_text_field( wp_unslash( $_FILES['cabsb_import_file']['tmp_name'] ) ) );
        $data = json_decode( $json, true );

        if ( empty( $data ) || ! is_array( $data ) ) {
            return;
        }

        if ( ! empty( $data['theme_mods'] ) && is_array( $data['theme_mods'] ) ) {
            foreach ( $data['theme_mods'] as $key => $value ) {
                set_theme_mod( sanitize_key( $key ), sanitize_text_field( wp_unslash( $value ) ) );
            }
        }

        if ( ! empty( $data['options'] ) && is_array( $data['options'] ) ) {
            foreach ( $data['options'] as $key => $value ) {
                update_option( sanitize_key( $key ), wp_kses_post( $value ), false );
            }
        }
    }
}

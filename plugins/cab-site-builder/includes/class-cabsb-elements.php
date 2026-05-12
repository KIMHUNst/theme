<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Elements {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_type' ) );
        add_action( 'add_meta_boxes', array( __CLASS__, 'meta_boxes' ) );
        add_action( 'save_post_cabsb_element', array( __CLASS__, 'save_meta' ) );
        add_action( 'wp_body_open', array( __CLASS__, 'render_header_elements' ) );
        add_action( 'wp_footer', array( __CLASS__, 'render_footer_elements' ), 5 );
    }

    public static function register_post_type() {
        register_post_type( 'cabsb_element', array(
            'labels' => array(
                'name'          => __( 'CAB Elements', 'cab-site-builder' ),
                'singular_name' => __( 'CAB Element', 'cab-site-builder' ),
            ),
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => 'cab-site-builder',
            'supports'     => array( 'title', 'editor' ),
            'menu_icon'    => 'dashicons-screenoptions',
        ) );
    }

    public static function meta_boxes() {
        add_meta_box(
            'cabsb-element-settings',
            __( 'Element Settings', 'cab-site-builder' ),
            array( __CLASS__, 'settings_box' ),
            'cabsb_element',
            'side'
        );
    }

    public static function settings_box( $post ) {
        wp_nonce_field( 'cabsb_element_settings', 'cabsb_element_nonce' );

        $location = get_post_meta( $post->ID, '_cabsb_location', true );
        $display  = get_post_meta( $post->ID, '_cabsb_display', true );
        ?>
        <p>
            <label><strong><?php esc_html_e( 'Location', 'cab-site-builder' ); ?></strong></label>
            <select name="cabsb_location" style="width:100%;margin-top:6px;">
                <option value="header" <?php selected( $location, 'header' ); ?>>Header</option>
                <option value="footer" <?php selected( $location, 'footer' ); ?>>Footer</option>
                <option value="before_content" <?php selected( $location, 'before_content' ); ?>>Before Content</option>
            </select>
        </p>

        <p>
            <label><strong><?php esc_html_e( 'Display Rules', 'cab-site-builder' ); ?></strong></label>
            <select name="cabsb_display" style="width:100%;margin-top:6px;">
                <option value="entire_site" <?php selected( $display, 'entire_site' ); ?>>Entire Site</option>
                <option value="single" <?php selected( $display, 'single' ); ?>>Single Posts</option>
                <option value="homepage" <?php selected( $display, 'homepage' ); ?>>Homepage</option>
            </select>
        </p>
        <?php
    }

    public static function save_meta( $post_id ) {
        if ( empty( $_POST['cabsb_element_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cabsb_element_nonce'] ) ), 'cabsb_element_settings' ) ) {
            return;
        }

        update_post_meta( $post_id, '_cabsb_location', sanitize_key( wp_unslash( $_POST['cabsb_location'] ?? '' ) ) );
        update_post_meta( $post_id, '_cabsb_display', sanitize_key( wp_unslash( $_POST['cabsb_display'] ?? '' ) ) );
    }

    public static function render_header_elements() {
        self::render_location( 'header' );
    }

    public static function render_footer_elements() {
        self::render_location( 'footer' );
    }

    private static function render_location( $location ) {
        $elements = get_posts( array(
            'post_type'      => 'cabsb_element',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_key'       => '_cabsb_location',
            'meta_value'     => $location,
        ) );

        foreach ( $elements as $element ) {
            $display = get_post_meta( $element->ID, '_cabsb_display', true );

            if ( 'single' === $display && ! is_single() ) {
                continue;
            }

            if ( 'homepage' === $display && ! is_front_page() ) {
                continue;
            }

            echo '<section class="cabsb-element cabsb-location-' . esc_attr( $location ) . '">';
            echo apply_filters( 'the_content', $element->post_content );
            echo '</section>';
        }
    }
}

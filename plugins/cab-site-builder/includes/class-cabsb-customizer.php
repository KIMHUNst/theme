<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Customizer {

    public static function init() {
        add_action( 'customize_register', array( __CLASS__, 'register' ) );
        add_action( 'wp_head', array( __CLASS__, 'dynamic_css' ), 30 );
    }

    public static function register( $wp_customize ) {
        $wp_customize->add_panel( 'cabsb_panel', array(
            'title'       => __( 'CAB Site Builder', 'cab-site-builder' ),
            'description' => __( 'Global layout, colors, typography and spacing controls.', 'cab-site-builder' ),
            'priority'    => 25,
        ) );

        $wp_customize->add_section( 'cabsb_layout', array(
            'title' => __( 'Layout', 'cab-site-builder' ),
            'panel' => 'cabsb_panel',
        ) );

        $wp_customize->add_setting( 'cabsb_container_width', array(
            'default'           => 1200,
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control( 'cabsb_container_width', array(
            'label'       => __( 'Container Width', 'cab-site-builder' ),
            'section'     => 'cabsb_layout',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 720, 'max' => 1600, 'step' => 10 ),
        ) );

        $wp_customize->add_setting( 'cabsb_content_spacing', array(
            'default'           => 32,
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control( 'cabsb_content_spacing', array(
            'label'       => __( 'Content Spacing', 'cab-site-builder' ),
            'section'     => 'cabsb_layout',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 2 ),
        ) );

        $wp_customize->add_section( 'cabsb_design', array(
            'title' => __( 'Colors & Typography', 'cab-site-builder' ),
            'panel' => 'cabsb_panel',
        ) );

        $wp_customize->add_setting( 'cabsb_accent_color', array(
            'default'           => '#2563eb',
            'sanitize_callback' => 'sanitize_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cabsb_accent_color', array(
            'label'   => __( 'Accent Color', 'cab-site-builder' ),
            'section' => 'cabsb_design',
        ) ) );

        $wp_customize->add_setting( 'cabsb_body_font_size', array(
            'default'           => 17,
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control( 'cabsb_body_font_size', array(
            'label'       => __( 'Body Font Size', 'cab-site-builder' ),
            'section'     => 'cabsb_design',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 13, 'max' => 24, 'step' => 1 ),
        ) );
    }

    public static function dynamic_css() {
        $container = absint( get_theme_mod( 'cabsb_container_width', 1200 ) );
        $spacing   = absint( get_theme_mod( 'cabsb_content_spacing', 32 ) );
        $accent    = sanitize_hex_color( get_theme_mod( 'cabsb_accent_color', '#2563eb' ) );
        $font_size = absint( get_theme_mod( 'cabsb_body_font_size', 17 ) );
        ?>
        <style id="cabsb-dynamic-css">
            :root {
                --cabsb-container: <?php echo esc_html( $container ); ?>px;
                --cabsb-spacing: <?php echo esc_html( $spacing ); ?>px;
                --cabsb-accent: <?php echo esc_html( $accent ); ?>;
                --cabsb-font-size: <?php echo esc_html( $font_size ); ?>px;
            }
        </style>
        <?php
    }
}

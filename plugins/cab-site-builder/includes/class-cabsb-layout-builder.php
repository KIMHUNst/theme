<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Layout_Builder {

    public static function init() {
        add_action( 'customize_register', array( __CLASS__, 'customizer' ) );
        add_action( 'wp_head', array( __CLASS__, 'layout_css' ), 40 );
        add_action( 'wp_body_open', array( __CLASS__, 'render_sticky_bar' ) );
    }

    public static function customizer( $wp_customize ) {
        $wp_customize->add_section( 'cabsb_header_builder', array(
            'title' => __( 'Header Builder', 'cab-site-builder' ),
            'panel' => 'cabsb_panel',
        ) );

        $wp_customize->add_setting( 'cabsb_header_height', array(
            'default' => 80,
            'sanitize_callback' => 'absint',
        ) );

        $wp_customize->add_control( 'cabsb_header_height', array(
            'label' => __( 'Header Height', 'cab-site-builder' ),
            'section' => 'cabsb_header_builder',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 50,
                'max' => 180,
            ),
        ) );

        $wp_customize->add_setting( 'cabsb_enable_sticky_header', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ) );

        $wp_customize->add_control( 'cabsb_enable_sticky_header', array(
            'label' => __( 'Enable Sticky Header', 'cab-site-builder' ),
            'section' => 'cabsb_header_builder',
            'type' => 'checkbox',
        ) );

        $wp_customize->add_section( 'cabsb_footer_builder', array(
            'title' => __( 'Footer Builder', 'cab-site-builder' ),
            'panel' => 'cabsb_panel',
        ) );

        $wp_customize->add_setting( 'cabsb_footer_columns', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
        ) );

        $wp_customize->add_control( 'cabsb_footer_columns', array(
            'label' => __( 'Footer Columns', 'cab-site-builder' ),
            'section' => 'cabsb_footer_builder',
            'type' => 'select',
            'choices' => array(
                1 => '1 Column',
                2 => '2 Columns',
                3 => '3 Columns',
                4 => '4 Columns',
            ),
        ) );
    }

    public static function layout_css() {
        ?>
        <style id="cabsb-layout-builder-css">
            .site-header {
                min-height: <?php echo absint( get_theme_mod( 'cabsb_header_height', 80 ) ); ?>px;
                <?php if ( get_theme_mod( 'cabsb_enable_sticky_header', true ) ) : ?>
                position: sticky;
                top: 0;
                z-index: 999;
                <?php endif; ?>
            }

            .site-footer {
                display: grid;
                grid-template-columns: repeat(<?php echo absint( get_theme_mod( 'cabsb_footer_columns', 4 ) ); ?>, minmax(0,1fr));
                gap: 24px;
            }
        </style>
        <?php
    }

    public static function render_sticky_bar() {
        if ( ! get_theme_mod( 'cabsb_enable_sticky_header', true ) ) {
            return;
        }

        echo '<div class="cabsb-sticky-helper" aria-hidden="true"></div>';
    }
}

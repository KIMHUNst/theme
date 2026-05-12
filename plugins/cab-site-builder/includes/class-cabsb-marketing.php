<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Marketing {

    public static function init() {
        add_action( 'customize_register', array( __CLASS__, 'customizer' ) );
        add_action( 'wp_footer', array( __CLASS__, 'render_popup' ) );
        add_action( 'wp_footer', array( __CLASS__, 'render_sticky_cta' ), 15 );
    }

    public static function customizer( $wp_customize ) {
        $wp_customize->add_section( 'cabsb_marketing', array(
            'title' => __( 'Marketing Features', 'cab-site-builder' ),
            'panel' => 'cabsb_panel',
        ) );

        $wp_customize->add_setting( 'cabsb_enable_popup', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ) );

        $wp_customize->add_control( 'cabsb_enable_popup', array(
            'label' => __( 'Enable Popup', 'cab-site-builder' ),
            'section' => 'cabsb_marketing',
            'type' => 'checkbox',
        ) );

        $wp_customize->add_setting( 'cabsb_popup_content', array(
            'default' => 'Subscribe for updates and premium resources.',
            'sanitize_callback' => 'wp_kses_post',
        ) );

        $wp_customize->add_control( 'cabsb_popup_content', array(
            'label' => __( 'Popup Content', 'cab-site-builder' ),
            'section' => 'cabsb_marketing',
            'type' => 'textarea',
        ) );

        $wp_customize->add_setting( 'cabsb_enable_sticky_cta', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ) );

        $wp_customize->add_control( 'cabsb_enable_sticky_cta', array(
            'label' => __( 'Enable Sticky CTA', 'cab-site-builder' ),
            'section' => 'cabsb_marketing',
            'type' => 'checkbox',
        ) );
    }

    public static function render_popup() {
        if ( ! get_theme_mod( 'cabsb_enable_popup', false ) ) {
            return;
        }

        ?>
        <div class="cabsb-popup-overlay" data-cabsb-popup>
            <div class="cabsb-popup-box">
                <button class="cabsb-popup-close" data-cabsb-popup-close>&times;</button>
                <div class="cabsb-popup-content">
                    <?php echo wp_kses_post( wpautop( get_theme_mod( 'cabsb_popup_content', '' ) ) ); ?>
                </div>
            </div>
        </div>
        <?php
    }

    public static function render_sticky_cta() {
        if ( ! get_theme_mod( 'cabsb_enable_sticky_cta', true ) ) {
            return;
        }

        echo '<div class="cabsb-sticky-cta"><span>Build faster with CAB Site Builder.</span><a href="#">Get Started</a></div>';
    }
}

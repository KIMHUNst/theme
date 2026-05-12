<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Shortcodes {

    public static function init() {
        add_shortcode( 'cabsb_container', array( __CLASS__, 'container' ) );
        add_shortcode( 'cabsb_grid', array( __CLASS__, 'grid' ) );
        add_shortcode( 'cabsb_button', array( __CLASS__, 'button' ) );
        add_shortcode( 'cabsb_hero', array( __CLASS__, 'hero' ) );
    }

    public static function container( $atts, $content = null ) {
        $atts = shortcode_atts( array(
            'width' => '1200px',
        ), $atts, 'cabsb_container' );

        return '<div class="cabsb-container" style="max-width:' . esc_attr( $atts['width'] ) . ';">' . do_shortcode( $content ) . '</div>';
    }

    public static function grid( $atts, $content = null ) {
        $atts = shortcode_atts( array(
            'columns' => 3,
            'gap'     => 24,
        ), $atts, 'cabsb_grid' );

        return '<div class="cabsb-grid" style="display:grid;grid-template-columns:repeat(' . absint( $atts['columns'] ) . ',minmax(0,1fr));gap:' . absint( $atts['gap'] ) . 'px;">' . do_shortcode( $content ) . '</div>';
    }

    public static function button( $atts ) {
        $atts = shortcode_atts( array(
            'text' => 'Learn More',
            'url'  => '#',
            'style'=> 'primary',
        ), $atts, 'cabsb_button' );

        return '<a class="cabsb-button cabsb-button-' . esc_attr( $atts['style'] ) . '" href="' . esc_url( $atts['url'] ) . '">' . esc_html( $atts['text'] ) . '</a>';
    }

    public static function hero( $atts, $content = null ) {
        $atts = shortcode_atts( array(
            'title'    => 'Hero Section',
            'subtitle' => 'Build modern landing sections visually.',
        ), $atts, 'cabsb_hero' );

        return '<section class="cabsb-hero"><div class="cabsb-container"><h1>' . esc_html( $atts['title'] ) . '</h1><p>' . esc_html( $atts['subtitle'] ) . '</p><div class="cabsb-hero-content">' . do_shortcode( $content ) . '</div></div></section>';
    }
}

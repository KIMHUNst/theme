<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Template_Library {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
    }

    public static function menu() {
        add_submenu_page(
            'cab-site-builder',
            __( 'Template Library', 'cab-site-builder' ),
            __( 'Template Library', 'cab-site-builder' ),
            'manage_options',
            'cabsb-template-library',
            array( __CLASS__, 'page' )
        );
    }

    public static function page() {
        $templates = array(
            array( 'title' => 'SEO Agency Hero', 'type' => 'Hero Section' ),
            array( 'title' => 'SaaS Pricing Grid', 'type' => 'Pricing Layout' ),
            array( 'title' => 'Affiliate Review CTA', 'type' => 'Conversion Section' ),
            array( 'title' => 'Minimal Blog Header', 'type' => 'Header Layout' ),
        );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CAB Template Library', 'cab-site-builder' ); ?></h1>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-top:24px;">
                <?php foreach ( $templates as $template ) : ?>
                    <div class="card" style="padding:20px;">
                        <h2><?php echo esc_html( $template['title'] ); ?></h2>
                        <p><?php echo esc_html( $template['type'] ); ?></p>
                        <button class="button button-primary" disabled><?php esc_html_e( 'Import Soon', 'cab-site-builder' ); ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

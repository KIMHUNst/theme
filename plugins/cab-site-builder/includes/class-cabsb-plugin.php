<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Plugin {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );

        CABSB_Settings::init();
        CABSB_Customizer::init();
        CABSB_Elements::init();
        CABSB_Hooks::init();
        CABSB_Shortcodes::init();
        CABSB_Performance::init();
        CABSB_Layout_Builder::init();
        CABSB_Asset_Manager::init();
        CABSB_Marketing::init();
        CABSB_Navigation::init();
        CABSB_AI_Builder::init();
        CABSB_Template_Library::init();
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'cabsb-style',
            CABSB_URL . 'assets/css/cabsb.css',
            array(),
            CABSB_VERSION
        );

        wp_enqueue_script(
            'cabsb-script',
            CABSB_URL . 'assets/js/cabsb.js',
            array(),
            CABSB_VERSION,
            true
        );
    }

    public function admin_menu() {
        add_menu_page(
            __( 'CAB Site Builder', 'cab-site-builder' ),
            __( 'CAB Builder', 'cab-site-builder' ),
            'manage_options',
            'cab-site-builder',
            array( $this, 'dashboard_page' ),
            'dashicons-layout',
            58
        );
    }

    public function dashboard_page() {
        ?>
        <div class="wrap">
            <h1>CAB Site Builder</h1>
            <p>GeneratePress-style modular builder for WordPress.</p>

            <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px;margin-top:24px;">
                <div class="card"><h2>Layout</h2><p>Container, sidebar, width, spacing.</p></div>
                <div class="card"><h2>Typography</h2><p>Fonts, scale, body/headings.</p></div>
                <div class="card"><h2>Performance</h2><p>CSS/JS optimization and preload.</p></div>
                <div class="card"><h2>AI & Templates</h2><p>Generate layouts and reusable sections.</p></div>
            </div>
        </div>
        <?php
    }

    public static function activate() {
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

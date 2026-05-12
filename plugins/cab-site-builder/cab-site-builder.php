<?php
/**
 * Plugin Name: CAB Site Builder
 * Plugin URI: https://github.com/KIMHUNst/theme
 * Description: Lightweight GeneratePress-style site builder plugin with layout controls, typography, colors, hooks, elements, performance options, and reusable blocks.
 * Version: 1.0.0
 * Author: KIMHUNst
 * Text Domain: cab-site-builder
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CABSB_VERSION', '1.0.0' );
define( 'CABSB_PATH', plugin_dir_path( __FILE__ ) );
define( 'CABSB_URL', plugin_dir_url( __FILE__ ) );

require_once CABSB_PATH . 'includes/class-cabsb-plugin.php';
require_once CABSB_PATH . 'includes/class-cabsb-settings.php';
require_once CABSB_PATH . 'includes/class-cabsb-customizer.php';
require_once CABSB_PATH . 'includes/class-cabsb-elements.php';
require_once CABSB_PATH . 'includes/class-cabsb-hooks.php';
require_once CABSB_PATH . 'includes/class-cabsb-shortcodes.php';
require_once CABSB_PATH . 'includes/class-cabsb-performance.php';

function cabsb_boot() {
    CABSB_Plugin::instance();
}
add_action( 'plugins_loaded', 'cabsb_boot' );

register_activation_hook( __FILE__, array( 'CABSB_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CABSB_Plugin', 'deactivate' ) );

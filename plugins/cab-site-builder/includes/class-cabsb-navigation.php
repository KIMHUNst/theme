<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Navigation {

    public static function init() {
        add_action( 'wp_footer', array( __CLASS__, 'mobile_panel' ) );
        add_filter( 'nav_menu_css_class', array( __CLASS__, 'menu_classes' ), 10, 2 );
    }

    public static function mobile_panel() {
        ?>
        <div class="cabsb-offcanvas" data-cabsb-offcanvas>
            <button class="cabsb-offcanvas-close" data-cabsb-close>&times;</button>
            <div class="cabsb-offcanvas-content">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>
        </div>
        <?php
    }

    public static function menu_classes( $classes, $item ) {
        $children = in_array( 'menu-item-has-children', $classes, true );

        if ( $children ) {
            $classes[] = 'cabsb-mega-parent';
        }

        return $classes;
    }
}

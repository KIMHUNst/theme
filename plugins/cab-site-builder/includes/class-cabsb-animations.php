<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Animations {

    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'animation_css' ), 45 );
        add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
    }

    public static function body_classes( $classes ) {
        $classes[] = 'cabsb-animations-enabled';
        return $classes;
    }

    public static function animation_css() {
        ?>
        <style id="cabsb-animation-engine">
            .cabsb-reveal {
                opacity: 0;
                transform: translateY(40px);
                transition: opacity .8s ease, transform .8s ease;
            }

            .cabsb-reveal.visible {
                opacity: 1;
                transform: translateY(0);
            }

            .cabsb-hover-lift {
                transition: transform .25s ease, box-shadow .25s ease;
            }

            .cabsb-hover-lift:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 50px rgba(0,0,0,.12);
            }

            .cabsb-glass {
                background: rgba(255,255,255,.72);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
            }
        </style>
        <?php
    }
}

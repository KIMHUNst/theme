<?php
/**
 * Header template.
 *
 * @package Clean_Approval_Blog
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="header-inner">
        <div class="site-branding">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            }
            ?>
            <div>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php bloginfo( 'name' ); ?>
                </a>
                <p class="site-description">
                    <?php bloginfo( 'description' ); ?>
                </p>
            </div>
        </div>

        <nav class="main-navigation" aria-label="Primary Menu">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
            ) );
            ?>
        </nav>
    </div>
</header>

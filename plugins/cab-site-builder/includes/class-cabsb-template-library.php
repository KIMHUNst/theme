<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CABSB_Template_Library {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'import_template' ) );
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
            'seo-hero' => array(
                'title' => 'SEO Agency Hero',
                'type'  => 'Hero Section',
            ),
            'pricing-grid' => array(
                'title' => 'SaaS Pricing Grid',
                'type'  => 'Pricing Layout',
            ),
            'affiliate-cta' => array(
                'title' => 'Affiliate Review CTA',
                'type'  => 'Conversion Section',
            ),
            'blog-header' => array(
                'title' => 'Minimal Blog Header',
                'type'  => 'Header Layout',
            ),
        );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CAB Template Library', 'cab-site-builder' ); ?></h1>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-top:24px;">
                <?php foreach ( $templates as $slug => $template ) : ?>
                    <div class="card" style="padding:20px;">
                        <h2><?php echo esc_html( $template['title'] ); ?></h2>
                        <p><?php echo esc_html( $template['type'] ); ?></p>
                        <form method="post">
                            <?php wp_nonce_field( 'cabsb_import_template', 'cabsb_template_nonce' ); ?>
                            <input type="hidden" name="cabsb_template_slug" value="<?php echo esc_attr( $slug ); ?>">
                            <?php submit_button( __( 'Import Template', 'cab-site-builder' ), 'primary', 'cabsb_import_template', false ); ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public static function import_template() {
        if ( empty( $_POST['cabsb_import_template'] ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) || empty( $_POST['cabsb_template_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cabsb_template_nonce'] ) ), 'cabsb_import_template' ) ) {
            return;
        }

        $slug = isset( $_POST['cabsb_template_slug'] ) ? sanitize_key( wp_unslash( $_POST['cabsb_template_slug'] ) ) : '';
        $content = self::template_content( $slug );

        if ( ! $content ) {
            return;
        }

        $post_id = wp_insert_post( array(
            'post_type'    => 'cabsb_element',
            'post_title'   => ucwords( str_replace( '-', ' ', $slug ) ),
            'post_content' => $content,
            'post_status'  => 'draft',
        ) );

        if ( $post_id ) {
            update_post_meta( $post_id, '_cabsb_location', 'before_content' );
            update_post_meta( $post_id, '_cabsb_display', 'homepage' );
        }
    }

    private static function template_content( $slug ) {
        switch ( $slug ) {
            case 'seo-hero':
                return '[cabsb_hero title="Rank Higher in Google" subtitle="SEO-focused landing layout with modern UI."][cabsb_button text="Get Started" url="#"][/cabsb_hero]';

            case 'pricing-grid':
                return '[cabsb_container][cabsb_grid columns="3"]<div class="cabsb-hover-lift"><h3>Starter</h3><p>$9/mo</p></div><div class="cabsb-hover-lift"><h3>Growth</h3><p>$29/mo</p></div><div class="cabsb-hover-lift"><h3>Scale</h3><p>$79/mo</p></div>[/cabsb_grid][/cabsb_container]';

            case 'affiliate-cta':
                return '[cabsb_hero title="Recommended Tools" subtitle="Conversion-focused affiliate section."][cabsb_button text="View Deals" url="#"][/cabsb_hero]';

            case 'blog-header':
                return '[cabsb_container]<div class="cabsb-glass" style="padding:40px;border-radius:28px;"><h1>Modern Blog Experience</h1><p>Minimal layout optimized for readability.</p></div>[/cabsb_container]';
        }

        return '';
    }
}

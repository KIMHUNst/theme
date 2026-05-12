<?php
/**
 * Premium feature pack for Clean Approval Blog.
 *
 * @package Clean_Approval_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cab_premium_assets() {
    wp_localize_script( 'clean-approval-blog-script', 'cabData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'cab_ajax_nonce' ),
    ) );

    echo '<link rel="manifest" href="' . esc_url( get_template_directory_uri() . '/manifest.json' ) . '">' . "\n";
    echo '<meta name="theme-color" content="' . esc_attr( get_theme_mod( 'cab_accent_color', '#2563eb' ) ) . '">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'cab_premium_assets' );

function cab_ajax_search() {
    check_ajax_referer( 'cab_ajax_nonce', 'nonce' );

    $term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

    if ( strlen( $term ) < 2 ) {
        wp_send_json_success( array() );
    }

    $query = new WP_Query( array(
        's'              => $term,
        'posts_per_page' => 6,
        'post_status'    => 'publish',
    ) );

    $results = array();

    while ( $query->have_posts() ) {
        $query->the_post();
        $results[] = array(
            'title' => get_the_title(),
            'url'   => get_permalink(),
            'date'  => get_the_date(),
        );
    }

    wp_reset_postdata();
    wp_send_json_success( $results );
}
add_action( 'wp_ajax_cab_ajax_search', 'cab_ajax_search' );
add_action( 'wp_ajax_nopriv_cab_ajax_search', 'cab_ajax_search' );

function cab_load_more_posts() {
    check_ajax_referer( 'cab_ajax_nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

    $query = new WP_Query( array(
        'post_status'    => 'publish',
        'paged'          => $page,
        'posts_per_page' => get_option( 'posts_per_page' ),
    ) );

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                <div class="post-card-body">
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta"><?php cab_posted_on(); ?><?php cab_categories(); ?></div>
                    <?php the_excerpt(); ?>
                    <a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More →', 'clean-approval-blog' ); ?></a>
                </div>
            </article>
            <?php
        }
    }

    wp_reset_postdata();

    wp_send_json_success( array(
        'html'    => ob_get_clean(),
        'hasMore' => $page < (int) $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_cab_load_more_posts', 'cab_load_more_posts' );
add_action( 'wp_ajax_nopriv_cab_load_more_posts', 'cab_load_more_posts' );

function cab_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'cab_design_options', array(
        'title'    => esc_html__( 'Clean Approval Design', 'clean-approval-blog' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'cab_accent_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cab_accent_color', array(
        'label'   => esc_html__( 'Accent Color', 'clean-approval-blog' ),
        'section' => 'cab_design_options',
    ) ) );

    $wp_customize->add_setting( 'cab_enable_infinite_scroll', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );

    $wp_customize->add_control( 'cab_enable_infinite_scroll', array(
        'label'   => esc_html__( 'Enable Infinite Scroll Button', 'clean-approval-blog' ),
        'section' => 'cab_design_options',
        'type'    => 'checkbox',
    ) );
}
add_action( 'customize_register', 'cab_customize_register' );

function cab_customizer_css() {
    $accent = get_theme_mod( 'cab_accent_color', '#2563eb' );
    echo '<style>:root{--cab-accent:' . esc_html( $accent ) . ';}</style>';
}
add_action( 'wp_head', 'cab_customizer_css', 20 );

function cab_faq_schema_shortcode( $atts, $content = null ) {
    if ( empty( $content ) ) {
        return '';
    }

    $items = array_filter( array_map( 'trim', explode( '|||', $content ) ) );
    $schema_items = array();
    $html = '<div class="cab-faq-box">';

    foreach ( $items as $item ) {
        $parts = array_map( 'trim', explode( '::', $item, 2 ) );
        if ( count( $parts ) !== 2 ) {
            continue;
        }

        $question = $parts[0];
        $answer   = $parts[1];
        $html .= '<details><summary>' . esc_html( $question ) . '</summary><p>' . esc_html( $answer ) . '</p></details>';
        $schema_items[] = array(
            '@type' => 'Question',
            'name' => $question,
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => $answer,
            ),
        );
    }

    $html .= '</div>';

    if ( ! empty( $schema_items ) ) {
        $html .= '<script type="application/ld+json">' . wp_json_encode( array(
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => $schema_items,
        ) ) . '</script>';
    }

    return $html;
}
add_shortcode( 'cab_faq', 'cab_faq_schema_shortcode' );

function cab_cta_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'title' => 'Recommended Resource',
        'text'  => 'Check this useful resource for more details.',
        'url'   => home_url( '/' ),
        'button'=> 'Learn More',
    ), $atts, 'cab_cta' );

    return '<div class="cab-cta"><h3>' . esc_html( $atts['title'] ) . '</h3><p>' . esc_html( $atts['text'] ) . '</p><a data-cab-track="cta" href="' . esc_url( $atts['url'] ) . '" rel="nofollow sponsored">' . esc_html( $atts['button'] ) . '</a></div>';
}
add_shortcode( 'cab_cta', 'cab_cta_shortcode' );

function cab_review_box_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'title'  => 'Product Review',
        'rating' => '4.5',
        'price'  => '',
        'url'    => '',
    ), $atts, 'cab_review' );

    $rating = min( 5, max( 0, (float) $atts['rating'] ) );
    $html = '<div class="cab-review-box"><h3>' . esc_html( $atts['title'] ) . '</h3><div class="cab-rating">★ ' . esc_html( $rating ) . ' / 5</div>';

    if ( $atts['price'] ) {
        $html .= '<p class="cab-price">' . esc_html( $atts['price'] ) . '</p>';
    }

    if ( $content ) {
        $html .= '<p>' . esc_html( wp_strip_all_tags( $content ) ) . '</p>';
    }

    if ( $atts['url'] ) {
        $html .= '<a data-cab-track="review" class="cab-review-button" href="' . esc_url( $atts['url'] ) . '" rel="nofollow sponsored">View Deal</a>';
    }

    $html .= '</div>';
    return $html;
}
add_shortcode( 'cab_review', 'cab_review_box_shortcode' );

function cab_newsletter_shortcode() {
    return '<form class="cab-newsletter" method="post"><h3>Get Useful Updates</h3><p>Subscribe for practical guides and fresh articles.</p><input type="hidden" name="cab_newsletter_nonce" value="' . esc_attr( wp_create_nonce( 'cab_newsletter' ) ) . '"><input type="email" name="cab_email" placeholder="you@example.com" required><button type="submit">Subscribe</button></form>';
}
add_shortcode( 'cab_newsletter', 'cab_newsletter_shortcode' );

function cab_handle_newsletter_submit() {
    if ( empty( $_POST['cab_email'] ) || empty( $_POST['cab_newsletter_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cab_newsletter_nonce'] ) ), 'cab_newsletter' ) ) {
        return;
    }

    $email = sanitize_email( wp_unslash( $_POST['cab_email'] ) );

    if ( ! is_email( $email ) ) {
        return;
    }

    $subscribers = get_option( 'cab_newsletter_subscribers', array() );
    if ( ! in_array( $email, $subscribers, true ) ) {
        $subscribers[] = $email;
        update_option( 'cab_newsletter_subscribers', $subscribers, false );
    }
}
add_action( 'init', 'cab_handle_newsletter_submit' );

function cab_bookmark_button_shortcode() {
    if ( ! is_singular() ) {
        return '';
    }

    return '<button class="cab-bookmark" data-bookmark-id="' . esc_attr( get_the_ID() ) . '">☆ Bookmark</button>';
}
add_shortcode( 'cab_bookmark', 'cab_bookmark_button_shortcode' );

function cab_internal_links_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 5 ), $atts, 'cab_internal_links' );
    $query = new WP_Query( array(
        'posts_per_page'      => absint( $atts['limit'] ),
        'post__not_in'        => is_singular() ? array( get_the_ID() ) : array(),
        'orderby'             => 'rand',
        'ignore_sticky_posts' => true,
    ) );

    if ( ! $query->have_posts() ) {
        return '';
    }

    $html = '<div class="cab-internal-links"><strong>Recommended Reading</strong><ul>';
    while ( $query->have_posts() ) {
        $query->the_post();
        $html .= '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
    }
    $html .= '</ul></div>';
    wp_reset_postdata();
    return $html;
}
add_shortcode( 'cab_internal_links', 'cab_internal_links_shortcode' );

function cab_track_click() {
    check_ajax_referer( 'cab_ajax_nonce', 'nonce' );
    $type = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : 'unknown';
    $stats = get_option( 'cab_click_stats', array() );
    $stats[ $type ] = isset( $stats[ $type ] ) ? (int) $stats[ $type ] + 1 : 1;
    update_option( 'cab_click_stats', $stats, false );
    wp_send_json_success( $stats );
}
add_action( 'wp_ajax_cab_track_click', 'cab_track_click' );
add_action( 'wp_ajax_nopriv_cab_track_click', 'cab_track_click' );

function cab_defer_noncritical_scripts( $tag, $handle ) {
    if ( 'clean-approval-blog-script' === $handle ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'cab_defer_noncritical_scripts', 10, 2 );

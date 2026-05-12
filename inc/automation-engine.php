<?php
/**
 * Automation engine: AI posting hooks, internal links, recommendations, and performance helpers.
 *
 * @package Clean_Approval_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cab_automation_menu() {
    add_theme_page(
        esc_html__( 'CAB Automation', 'clean-approval-blog' ),
        esc_html__( 'CAB Automation', 'clean-approval-blog' ),
        'manage_options',
        'cab-automation',
        'cab_automation_page'
    );
}
add_action( 'admin_menu', 'cab_automation_menu' );

function cab_register_automation_settings() {
    register_setting( 'cab_automation_group', 'cab_ai_api_endpoint', array( 'sanitize_callback' => 'esc_url_raw' ) );
    register_setting( 'cab_automation_group', 'cab_ai_api_key', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    register_setting( 'cab_automation_group', 'cab_ai_default_category', array( 'sanitize_callback' => 'absint' ) );
    register_setting( 'cab_automation_group', 'cab_enable_auto_internal_links', array( 'sanitize_callback' => 'rest_sanitize_boolean' ) );
    register_setting( 'cab_automation_group', 'cab_enable_recommendations', array( 'sanitize_callback' => 'rest_sanitize_boolean' ) );
    register_setting( 'cab_automation_group', 'cab_enable_performance_headers', array( 'sanitize_callback' => 'rest_sanitize_boolean' ) );
}
add_action( 'admin_init', 'cab_register_automation_settings' );

function cab_automation_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'CAB Automation Engine', 'clean-approval-blog' ); ?></h1>
        <p><?php esc_html_e( 'Configure AI posting, automatic internal links, recommendations, and performance helpers.', 'clean-approval-blog' ); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'cab_automation_group' ); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="cab_ai_api_endpoint">AI API Endpoint</label></th>
                    <td><input id="cab_ai_api_endpoint" name="cab_ai_api_endpoint" type="url" class="regular-text" value="<?php echo esc_attr( get_option( 'cab_ai_api_endpoint', '' ) ); ?>"><p class="description">Example: your own secure content-generation endpoint.</p></td>
                </tr>
                <tr>
                    <th scope="row"><label for="cab_ai_api_key">AI API Key</label></th>
                    <td><input id="cab_ai_api_key" name="cab_ai_api_key" type="password" class="regular-text" value="<?php echo esc_attr( get_option( 'cab_ai_api_key', '' ) ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="cab_ai_default_category">Default Category ID</label></th>
                    <td><input id="cab_ai_default_category" name="cab_ai_default_category" type="number" class="small-text" value="<?php echo esc_attr( get_option( 'cab_ai_default_category', 0 ) ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Automation Switches</th>
                    <td>
                        <label><input type="checkbox" name="cab_enable_auto_internal_links" value="1" <?php checked( get_option( 'cab_enable_auto_internal_links', true ) ); ?>> Auto internal links</label><br>
                        <label><input type="checkbox" name="cab_enable_recommendations" value="1" <?php checked( get_option( 'cab_enable_recommendations', true ) ); ?>> Machine-learning style recommendations</label><br>
                        <label><input type="checkbox" name="cab_enable_performance_headers" value="1" <?php checked( get_option( 'cab_enable_performance_headers', true ) ); ?>> Performance headers</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <h2><?php esc_html_e( 'Manual AI Draft Generator', 'clean-approval-blog' ); ?></h2>
        <form method="post">
            <?php wp_nonce_field( 'cab_ai_generate_draft', 'cab_ai_generate_nonce' ); ?>
            <p><input type="text" name="cab_ai_topic" class="regular-text" placeholder="Article topic"></p>
            <?php submit_button( esc_html__( 'Generate Draft', 'clean-approval-blog' ), 'secondary', 'cab_generate_draft' ); ?>
        </form>
    </div>
    <?php
}

function cab_handle_manual_ai_draft() {
    if ( ! is_admin() || empty( $_POST['cab_generate_draft'] ) ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) || empty( $_POST['cab_ai_generate_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cab_ai_generate_nonce'] ) ), 'cab_ai_generate_draft' ) ) {
        return;
    }

    $topic = isset( $_POST['cab_ai_topic'] ) ? sanitize_text_field( wp_unslash( $_POST['cab_ai_topic'] ) ) : '';
    if ( ! $topic ) {
        return;
    }

    $content = cab_generate_ai_content( $topic );
    if ( ! $content ) {
        $content = "# " . $topic . "\n\nThis draft was created as a placeholder because no AI endpoint is configured. Add your endpoint in CAB Automation to enable real generation.\n\n## Overview\n\nWrite a clear introduction here.\n\n## Key Points\n\nAdd helpful, original information here.\n\n## Conclusion\n\nSummarize the article and guide the reader to the next useful resource.";
    }

    wp_insert_post( array(
        'post_title'    => $topic,
        'post_content'  => $content,
        'post_status'   => 'draft',
        'post_category' => array_filter( array( absint( get_option( 'cab_ai_default_category', 0 ) ) ) ),
    ) );
}
add_action( 'admin_init', 'cab_handle_manual_ai_draft' );

function cab_generate_ai_content( $topic ) {
    $endpoint = get_option( 'cab_ai_api_endpoint', '' );
    $api_key  = get_option( 'cab_ai_api_key', '' );

    if ( ! $endpoint || ! $api_key ) {
        return '';
    }

    $response = wp_remote_post( $endpoint, array(
        'timeout' => 45,
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        ),
        'body' => wp_json_encode( array(
            'topic' => $topic,
            'type'  => 'wordpress_draft',
        ) ),
    ) );

    if ( is_wp_error( $response ) ) {
        return '';
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    return ! empty( $body['content'] ) ? wp_kses_post( $body['content'] ) : '';
}

function cab_auto_internal_link_content( $content ) {
    if ( ! get_option( 'cab_enable_auto_internal_links', true ) || ! is_single() || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }

    $posts = get_posts( array(
        'numberposts' => 8,
        'post_status' => 'publish',
        'exclude'     => array( get_the_ID() ),
    ) );

    foreach ( $posts as $post ) {
        $title = get_the_title( $post );
        if ( mb_strlen( $title ) < 5 || false === stripos( wp_strip_all_tags( $content ), $title ) ) {
            continue;
        }

        $link = '<a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( $title ) . '</a>';
        $content = preg_replace( '/' . preg_quote( $title, '/' ) . '/', $link, $content, 1 );
    }

    return $content;
}
add_filter( 'the_content', 'cab_auto_internal_link_content', 14 );

function cab_ml_recommendations( $limit = 4 ) {
    if ( ! get_option( 'cab_enable_recommendations', true ) ) {
        return;
    }

    $categories = is_singular() ? wp_get_post_categories( get_the_ID() ) : array();
    $args = array(
        'posts_per_page'      => absint( $limit ),
        'post__not_in'        => is_singular() ? array( get_the_ID() ) : array(),
        'meta_key'            => '_cab_post_views',
        'orderby'             => 'meta_value_num',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    );

    if ( $categories ) {
        $args['category__in'] = $categories;
    }

    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) {
        return;
    }

    echo '<section class="cab-ml-recommendations post-card"><div class="post-card-body"><h2>Smart Recommendations</h2><div class="related-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        echo '<article class="related-card"><h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3><p>' . esc_html( wp_trim_words( get_the_excerpt(), 18 ) ) . '</p></article>';
    }
    echo '</div></div></section>';
    wp_reset_postdata();
}

function cab_performance_headers() {
    if ( headers_sent() || ! get_option( 'cab_enable_performance_headers', true ) ) {
        return;
    }

    header( 'X-DNS-Prefetch-Control: on' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
}
add_action( 'send_headers', 'cab_performance_headers' );

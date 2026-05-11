<?php
/**
 * Related posts template part.
 *
 * @package Clean_Approval_Blog
 */

$categories = wp_get_post_categories( get_the_ID() );

if ( empty( $categories ) ) {
    return;
}

$related_query = new WP_Query( array(
    'category__in'        => $categories,
    'post__not_in'        => array( get_the_ID() ),
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true,
) );

if ( ! $related_query->have_posts() ) {
    wp_reset_postdata();
    return;
}
?>
<section class="related-posts post-card">
    <div class="post-card-body">
        <h2 class="related-title">
            <?php esc_html_e( 'Related Articles', 'clean-approval-blog' ); ?>
        </h2>

        <div class="related-grid">
            <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                <article class="related-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a class="related-thumb" href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'medium' ); ?>
                        </a>
                    <?php endif; ?>

                    <h3>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();

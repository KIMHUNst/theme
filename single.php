<?php
/**
 * Single post template.
 *
 * @package Clean_Approval_Blog
 */

get_header();
?>
<main class="site-main">
    <div class="content-area">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                <div class="post-card-body">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <div class="entry-meta">
                            <?php cab_posted_on(); ?>
                            <?php cab_categories(); ?>
                        </div>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="featured-image">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="ad-slot">
                        AdSense Placeholder - Before Content
                    </div>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clean-approval-blog' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <div class="ad-slot">
                        AdSense Placeholder - After Content
                    </div>

                    <footer class="entry-footer">
                        <?php the_tags( '<span class="tags-links">Tags: ', ', ', '</span>' ); ?>
                    </footer>
                </div>
            </article>

            <?php get_template_part( 'template-parts/related-posts' ); ?>

            <nav class="pagination" aria-label="Post navigation">
                <?php the_post_navigation(); ?>
            </nav>

            <?php
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
            ?>
        <?php endwhile; ?>
    </div>

    <?php get_sidebar(); ?>
</main>
<?php
get_footer();

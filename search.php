<?php
/**
 * Search results template.
 *
 * @package Clean_Approval_Blog
 */

get_header();
?>
<main class="site-main">
    <div class="content-area">
        <header class="post-card">
            <div class="post-card-body">
                <h1 class="entry-title">
                    <?php
                    printf(
                        esc_html__( 'Search results for: %s', 'clean-approval-blog' ),
                        '<span>' . esc_html( get_search_query() ) . '</span>'
                    );
                    ?>
                </h1>
                <?php get_search_form(); ?>
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="post-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                        <div class="post-card-body">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="entry-meta">
                                <?php cab_posted_on(); ?>
                                <?php cab_categories(); ?>
                            </div>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php the_posts_pagination(); ?>
            </div>
        <?php else : ?>
            <article class="post-card">
                <div class="post-card-body">
                    <h2><?php esc_html_e( 'Nothing found', 'clean-approval-blog' ); ?></h2>
                    <p><?php esc_html_e( 'Try a different keyword or browse recent articles.', 'clean-approval-blog' ); ?></p>
                </div>
            </article>
        <?php endif; ?>
    </div>

    <?php get_sidebar(); ?>
</main>
<?php
get_footer();

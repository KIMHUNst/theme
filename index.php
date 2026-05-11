<?php
/**
 * Main index template.
 *
 * @package Clean_Approval_Blog
 */

get_header();
?>
<main class="site-main">
    <div class="content-area">
        <?php if ( have_posts() ) : ?>
            <div class="post-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'large' ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="post-card-body">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="entry-meta">
                                    <?php cab_posted_on(); ?>
                                    <?php cab_categories(); ?>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <a class="read-more" href="<?php the_permalink(); ?>">
                                Read More →
                            </a>
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
                    <h1>No posts found.</h1>
                    <p>Start writing your first article.</p>
                </div>
            </article>
        <?php endif; ?>
    </div>

    <?php get_sidebar(); ?>
</main>
<?php
get_footer();

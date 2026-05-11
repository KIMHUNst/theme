<?php
/**
 * Archive template.
 *
 * @package Clean_Approval_Blog
 */

get_header();
?>
<main class="site-main">
    <div class="content-area">
        <header class="post-card">
            <div class="post-card-body">
                <h1 class="entry-title"><?php the_archive_title(); ?></h1>
                <div class="entry-content">
                    <?php the_archive_description( '<p>', '</p>' ); ?>
                </div>
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
                            </div>

                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php the_posts_pagination(); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php get_sidebar(); ?>
</main>
<?php
get_footer();

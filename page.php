<?php
/**
 * Page template.
 *
 * @package Clean_Approval_Blog
 */

get_header();
?>
<main class="site-main">
    <div class="content-area">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                <div class="post-card-body">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="featured-image">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

    <?php get_sidebar(); ?>
</main>
<?php
get_footer();

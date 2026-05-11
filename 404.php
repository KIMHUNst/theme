<?php
/**
 * 404 template.
 *
 * @package Clean_Approval_Blog
 */

get_header();
?>
<main class="site-main">
    <div class="content-area">
        <article class="post-card">
            <div class="post-card-body">
                <h1 class="entry-title">404</h1>
                <div class="entry-content">
                    <p>The page you are looking for could not be found.</p>
                    <p>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            Return to homepage
                        </a>
                    </p>
                </div>
            </div>
        </article>
    </div>

    <?php get_sidebar(); ?>
</main>
<?php
get_footer();

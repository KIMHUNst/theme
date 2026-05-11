<?php
/**
 * Sidebar template.
 *
 * @package Clean_Approval_Blog
 */
?>
<aside class="widget-area">
    <section class="widget">
        <h2 class="widget-title">About This Site</h2>
        <p>
            A clean informational blog theme designed for readability,
            SEO structure, and fast loading.
        </p>
    </section>

    <section class="widget">
        <div class="ad-slot">
            AdSense Placeholder<br>
            Insert your ad code here.
        </div>
    </section>

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php endif; ?>
</aside>

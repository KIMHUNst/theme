<?php
/**
 * Footer template.
 *
 * @package Clean_Approval_Blog
 */
?>
<footer class="site-footer">
    <div class="footer-inner">
        <p>
            © <?php echo esc_html( date_i18n( 'Y' ) ); ?>
            <?php bloginfo( 'name' ); ?>.
            Powered by WordPress.
        </p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

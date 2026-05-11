<?php
/**
 * Search form template.
 *
 * @package Clean_Approval_Blog
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="s">
        <?php esc_html_e( 'Search for:', 'clean-approval-blog' ); ?>
    </label>
    <input type="search" id="s" class="search-field" placeholder="<?php echo esc_attr_x( 'Search articles...', 'placeholder', 'clean-approval-blog' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
    <button type="submit" class="search-submit">
        <?php echo esc_html_x( 'Search', 'submit button', 'clean-approval-blog' ); ?>
    </button>
</form>

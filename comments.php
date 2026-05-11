<?php
/**
 * Comments template.
 *
 * @package Clean_Approval_Blog
 */

if ( post_password_required() ) {
    return;
}
?>
<section id="comments" class="comments-area post-card">
    <div class="post-card-body">
        <?php if ( have_comments() ) : ?>
            <h2 class="comments-title">
                <?php
                printf(
                    esc_html(
                        _nx(
                            'One comment',
                            '%1$s comments',
                            get_comments_number(),
                            'comments title',
                            'clean-approval-blog'
                        )
                    ),
                    number_format_i18n( get_comments_number() )
                );
                ?>
            </h2>

            <ol class="comment-list">
                <?php
                wp_list_comments( array(
                    'style'      => 'ol',
                    'short_ping' => true,
                ) );
                ?>
            </ol>

            <?php the_comments_navigation(); ?>
        <?php endif; ?>

        <?php
        comment_form( array(
            'class_submit' => 'submit-button',
        ) );
        ?>
    </div>
</section>

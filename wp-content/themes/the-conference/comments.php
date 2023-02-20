<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package The Conference
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comment-title">
			<span class="title-wrap">
			<?php
				printf( // WPCS: XSS OK.
					/* translators: 1: comment count number. */
					esc_html( _nx( '%1$s Comment', '%1$s Comments', get_comments_number(), 'comments title', 'the-conference' ) ),
					number_format_i18n( get_comments_number() )
				);
			?>
			</span>
		</h2><!-- .comments-title -->

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
                    'callback'   => 'the_conference_theme_comment',
                    'avatar_size' => 85,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'the-conference' ); ?></p>
		<?php
		endif;

	endif; // Check for have_comments().

	$args = array(
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title"><span class="title-wrap">',
		'title_reply_after' => '</span></h3>'
	);

	comment_form( $args );
	?>

</div><!-- #comments -->

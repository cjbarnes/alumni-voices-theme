<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

	$post_custom = get_post_custom();
	$bio = '<p>'.implode('</p>\n<p>', $post_custom['ap_author_bio']).'</p>';
	$name = implode(',', $post_custom['ap_author_name']);

?>

<div class="author-info">
	<h2 class="author-heading"><?php _e( 'Published by', 'twentyfifteen' ); ?></h2>
	<!--div class="author-avatar">
		<?php
		/**
		 * Filter the author bio avatar size.
		 *
		 * @since Twenty Fifteen 1.0
		 *
		 * @param int $size The avatar height and width size in pixels.
		 */
		$author_bio_avatar_size = apply_filters( 'twentyfifteen_author_bio_avatar_size', 56 );

		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		?>
	</div--><!-- .author-avatar -->

	<div class="author-description">
		<h3 class="author-title"><?php echo $name; ?></h3>

		<p class="author-bio">
			<?php echo $bio; ?>
			<!--a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php printf( __( 'View all posts by %s', 'twentyfifteen' ), get_the_author() ); ?>
			</a-->
		</p><!-- .author-bio -->

	</div><!-- .author-description -->
</div><!-- .author-info -->
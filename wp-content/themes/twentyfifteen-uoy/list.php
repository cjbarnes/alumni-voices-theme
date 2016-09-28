<?php
/**
 * The default template for displaying content
 *
 * Used for index/archive/search.
 *
 * @package UniversityOfYork
 * @subpackage Twenty_Fifteen_UoY
 * @since Twenty Fifteen UoY 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php

		// If it's a custom post
		$post_custom = get_post_custom();

	?>

	<header class="entry-header">
		<?php

			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

			if (isset($post_custom['ap_author_name'])) {

				$name = implode(', ', $post_custom['ap_author_name']);

		?>
		<div class="posted-on user-profile user-profile--compact"><div class="user-profile__meta">Posted by <?php echo $name; ?> on <?php echo get_the_date(); ?></div></div>
		<?php

			} else {

			  $img = get_avatar(get_the_author_meta('ID'));
		?>
		<div class="posted-on user-profile user-profile--compact"><div class="user-profile__image"><?php echo $img; ?></div> <div class="user-profile__meta">Posted by <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('display_name'); ?></a> on <?php echo get_the_date(); ?></div></div>
		<?php
			}
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

			/* translators: %s: Name of current post */
			the_excerpt( sprintf(
				__( 'Continue reading %s', 'twentyfifteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

		?>
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		// if (isset($post_custom['ap_author_bio'])) {
		// 	get_template_part( 'guest-bio' );
		// } elseif ( is_single() && get_the_author_meta( 'description' ) ) {
		// 	get_template_part( 'author-bio' );
		// }
	?>

	<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

</article><!-- #post-## -->

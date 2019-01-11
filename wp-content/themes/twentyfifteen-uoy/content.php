<?php
/**
 * The template for displaying standards post content
 *
 * @package UniversityOfYork
 * @subpackage Twenty_Fifteen_UoY
 * @since Twenty Fifteen UoY 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php

			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );
			endif;

			// If it's a custom post
			$post_custom = get_post_custom();

			if (isset($post_custom['ap_author_name']) && is_array($post_custom['ap_author_name'])) {

				$name = implode(', ', $post_custom['ap_author_name']);

		?>
		<div class="posted-on user-profile user-profile--compact"><?php
			if (isset($post_custom['ap_author_avatar']) && is_array($post_custom['ap_author_avatar'])) {
				$avatar = implode("",$post_custom['ap_author_avatar']);
				echo '<div class="user-profile__image"><img src="'.$avatar.'" width="96" height="96" alt="'.$name.'" class="avatar avatar-96 wp-user-avatar wp-user-avatar-96 photo avatar-default"></div> ';
			}
			?><div class="user-profile__meta">Posted by <?php echo $name; ?> on <?php echo get_the_date(''); ?></div></div>
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
			the_content( sprintf(
				__( 'Continue reading %s', 'twentyfifteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		if (isset($post_custom['ap_author_bio'])) {
			get_template_part( 'guest-bio' );
		} elseif ( is_single() && get_the_author_meta( 'description' ) ) {
			get_template_part( 'author-bio' );
		}
	?>

	<footer class="entry-footer">
		<?php twentyfifteen_uoy_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

<?php
/**
 * The template for displaying author pages
 *
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php

		$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

		if ( have_posts() ) :

		?>

			<header class="page-header">
				<?php
	        $title = sprintf( __( 'Posts by %s' ), '<span class="vcard">' . get_the_author() . '</span>' );
	        echo '<h1 class="page-title">'.$title.'</h1>';
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
					echo do_shortcode("[user id=".$curauth->ID."]");
				?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 *
				get_template_part( 'content', get_post_format() );
				*/
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
						// Post thumbnail.
						twentyfifteen_post_thumbnail();
					?>

					<header class="entry-header">
						<?php
							the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						  $img = get_avatar(get_the_author_meta('ID'));
						?>
						<div class="posted-on user-profile user-profile--compact"><div class="user-profile__image"><?php echo $img; ?></div> <div class="user-profile__meta">Posted by <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('display_name'); ?></a> on <?php echo get_the_date(); ?></div></div>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php
							// Make an excerpt if none exists
							$post_excerpt = get_the_excerpt();
							if ($post_excerpt == '') {
								$excerpt_length = apply_filters('excerpt_length', 55);
								$post_excerpt = wp_trim_words(get_the_content(), $excerpt_length);
							}

						  $post_excerpt = strip_shortcodes($post_excerpt);

						  /*
							wp_link_pages( array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
								'separator'   => '<span class="screen-reader-text">, </span>',
							) );
							*/
						?>
						<p><?php echo $post_excerpt; ?></p>
					</div><!-- .entry-content -->

					<?php
						// Author bio.
						if ( is_single() && get_the_author_meta( 'description' ) ) :
							get_template_part( 'author-bio' );
						endif;
					?>

					<footer class="entry-footer">
						<?php twentyfifteen_uoy_entry_meta(); ?>
						<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-footer -->

				</article><!-- #post-## -->

				<?php
			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
				'next_text'          => __( 'Next page', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>

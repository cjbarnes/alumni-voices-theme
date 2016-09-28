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
		<main id="main" class="site-main list-page" role="main">

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

				get_template_part( 'list', get_post_format() );

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

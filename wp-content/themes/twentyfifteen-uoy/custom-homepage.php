<?php
/* Template Name: Custom homepage */

/**
 * The template for displaying our custom homepage
 * Displays the first post as a featured post (use a sticky post)
 * Then lists teasers for next 9 posts
 *
 * @package UniversityOfYork
 * @subpackage Twenty_Fifteen_UoY
 * @since Twenty Fifteen UoY 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

	<?php

		// Set pagination
		if (get_query_var('paged')) {
			$paged = get_query_var('paged');
		} else if (get_query_var('page')) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		// Get posts in order, sticky first
		$args = array(
			'paged' => $paged,
			'posts_per_page' => 10
		);
		query_posts($args);

		if (have_posts()) {

      while (have_posts()) {

      	the_post();

				// Make an excerpt if none exists
				$post_excerpt = get_the_excerpt();
				if ($post_excerpt == '') {
					$excerpt_length = apply_filters('excerpt_length', 55);
					$post_excerpt = wp_trim_words(strip_shortcodes(get_the_content()), $excerpt_length);
				}

		?>
		<div <?php post_class(); ?>>
			<?php
			if (is_sticky()) {
				echo '<span class="sticky-post">Featured post</span>';
				//echo '<div class="post-thumbnail">'.get_the_post_thumbnail().'</div>';
			}
			?>
			<header class="entry-header">
		  	<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php
			// If it's a custom post
			$post_custom = get_post_custom();

			if (isset($post_custom['ap_author_name'])) {

				$name = implode(', ', $post_custom['ap_author_name']);

		?>
		<div class="posted-on user-profile user-profile--compact"><div class="user-profile__meta">Posted by <?php echo $name; ?> on <?php echo get_the_date(''); ?></div></div>
		<?php

			} else {

				// $author_id = get_the_author_meta('ID', get_the_author());
				$author_id = get_the_author_id();
				$img = get_avatar($author_id); ?>
				<div class="posted-on user-profile user-profile--compact">
					<div class="user-profile__image"><?php echo $img; ?></div>
					<div class="user-profile__meta">Posted by <a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo get_the_author(); ?></a> on <?php echo get_the_date(''); ?></div>
				</div>
			<?php
			}
			?>
			</header><!-- .entry-header -->
			<div class="entry-content">
				<p><?php echo $post_excerpt; ?></p>
			</div><!-- .entry-content -->
			<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->'); ?>
		</div><?php

		}

		// Previous/next page navigation.
		the_posts_pagination( array(
			'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
			'next_text'          => __( 'Next page', 'twentyfifteen' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
		) );

  }
	?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

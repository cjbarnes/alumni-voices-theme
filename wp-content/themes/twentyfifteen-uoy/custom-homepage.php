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

		$sticky = get_option('sticky_posts');

		$sticky_args = array(
			'post__in' => $sticky,
			'posts_per_page' => 9,
			'ignore_sticky_posts' => 1
		);

		$sticky_posts = get_posts($sticky_args);

		$normal_count = 10 - count($sticky);

		$normal_args = array(
			'posts_per_page' => $normal_count,
			'ignore_sticky_posts' => 1
		);

		$normal_posts = get_posts($normal_args);

    $posts = array_merge($sticky_posts, $normal_posts);

		foreach ($posts as $i => $this_post) {

			// Make an excerpt if none exists
			$post_excerpt = $this_post->post_excerpt;
			if ($post_excerpt == '') {
				$excerpt_length = apply_filters('excerpt_length', 55);
				$post_excerpt = wp_trim_words($this_post->post_content, $excerpt_length);
			}

		  $post_excerpt = strip_shortcodes($post_excerpt);

		?>
  	<!-- <?php //print_r($this_post); ?>-->
		<div <?php post_class($this_post->ID); ?>>
			<?php
			if (is_sticky($this_post->ID)) {
				echo '<span class="sticky-post">Featured</span>';
				//echo '<div class="post-thumbnail">'.get_the_post_thumbnail($this_post->ID).'</div>';
			}
			?>
			<header class="entry-header">
		  	<h3 class="entry-title"><a href="<?php echo get_permalink($this_post->ID); ?>"><?php echo $this_post->post_title; ?></a></h3>
				<?php $img = get_avatar(get_the_author_meta('ID', $this_post->post_author)); ?>
				<div class="posted-on user-profile user-profile--compact">
					<div class="user-profile__image"><?php echo $img; ?></div>
					<div class="user-profile__meta">Posted by <a href="<?php echo get_author_posts_url(get_the_author_meta('ID', $this_post->post_author)); ?>"><?php echo get_the_author_meta('display_name', $this_post->post_author); ?></a> on <?php echo get_the_date('', $this_post->ID); ?></div>
				</div>
			</header><!-- .entry-header -->
			<div class="entry-content">
				<p><?php echo $post_excerpt; ?> <a href="<?php echo get_permalink($this_post->ID); ?>">Continue reading</a></p>
			</div><!-- .entry-content -->
			<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->', $this_post->ID ); ?>
		</div><?php

		}

		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

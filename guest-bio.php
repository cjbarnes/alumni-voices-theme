<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

	$post_custom = get_post_custom();
	$bio = "";
	if (isset($post_custom['ap_author_bio']) && is_array($post_custom['ap_author_bio'])) {
		$bio = implode('</p>\n<p class="author-bio">', $post_custom['ap_author_bio']);
	}
	$name = "";
	if (isset($post_custom['ap_author_name']) && is_array($post_custom['ap_author_name'])) {
		$name = implode(',', $post_custom['ap_author_name']);
	}
	$avatar = "";
	if (isset($post_custom['ap_author_avatar']) && is_array($post_custom['ap_author_avatar'])) {
		$avatar = implode("",$post_custom['ap_author_avatar']);
	}

?>

<div class="author-info">
	<h2 class="author-heading"><?php _e( 'Published by', 'twentyfifteen' ); ?></h2>
		<?php
		/**
		 * Show custom avatar if field is filled in
		 */
		if ($avatar !== '') {
			$author_bio_avatar_size = apply_filters( 'twentyfifteen_author_bio_avatar_size', 56 );
			echo '<div class="author-avatar">';
			echo '  <img src="'.$avatar.'" width="'.$author_bio_avatar_size.'" height="'.$author_bio_avatar_size.'" alt="" class="avatar avatar-'.$author_bio_avatar_size.' wp-user-avatar wp-user-avatar-'.$author_bio_avatar_size.' photo avatar-default">';
	  	echo '</div>';
	  }
		?>
	<!-- .author-avatar -->

	<div class="author-description">

		<h3 class="author-title"><?php echo $name; ?></h3>

		<p class="author-bio">
			<?php echo $bio; ?>
		</p><!-- .author-bio -->

	</div><!-- .author-description -->
</div><!-- .author-info -->

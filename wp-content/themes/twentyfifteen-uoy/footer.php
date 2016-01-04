<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<div class="cat-tags">
				<h3>Categories</h3>
				<ul>
				<?php
				$args = array(
					'title_li' => ''
				);
				wp_list_categories($args); ?>
				</ul>
			</div>
			<div class="cat-tags">
				<h3>Tags</h3>
				<?php
				wp_tag_cloud();//'format=list&unit=em&smallest=1&largest=1')
				?>
			</div>
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>

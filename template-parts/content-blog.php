<?php
/**
 * LS custom
 * 
 * Template part for displaying the blog list
 * in index.php when homepage is set to 
 * "your latest posts"
 * see the mod in the loop in index.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wileecoder
 */

?>
<?php
global $wp_query;
if ($wp_query->post_count === 1) {
	$columns = 'one-column';
} else {
	$columns = 'two-columns';
}
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class($columns); ?>>
		<header class="entry-header">
			<?php
			the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
			?>
			<div class="entry-meta">
				<?php
				wileecoder_posted_on();
				wileecoder_posted_by();
				?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<?php wileecoder_post_thumbnail(); ?>

		<div class="entry-content">
			<?php
			the_excerpt();
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php wileecoder_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</article><!-- #post-<?php the_ID(); ?> -->
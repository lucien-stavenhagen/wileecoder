<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wileecoder
 */

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer">
	<?php
	/* LS custom */
	if (has_nav_menu('footermenu')) {
		$site_info_class = "site-info";
	} else {
		$site_info_class = "site-info-nonav";
	}
	?>
	<div class=<?php echo $site_info_class ?>>
		<a href="<?php echo esc_url(__('https://wordpress.org/', 'wileecoder')); ?>">
			<?php
			/* translators: %s: CMS name, i.e. WordPress. */
			printf(esc_html__('Proudly powered by %s', 'wileecoder'), 'WordPress');
			?>
		</a>
		<span class="sep"> | </span>
		<?php
		/* translators: 1: Theme name, 2: Theme author. */
		printf(esc_html__('Theme: %1$s by %2$s.', 'wileecoder'), 'wileecoder', '<a href="http://underscores.me/">Underscores.me</a>');
		?>
	</div><!-- .site-info or .site-info-nonav-->
	<?php if (has_nav_menu('footermenu')) : ?>
		<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e('Footer Menu', 'wileecoder'); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footermenu',
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
				)
			);
			?>
		</nav><!-- .footer-navigation -->
	<?php endif; ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>
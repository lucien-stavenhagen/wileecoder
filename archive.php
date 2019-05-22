<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wileecoder
 */

get_header();
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<?php if (have_posts()) : ?>

			<header class="page-header">
				<?php
				the_archive_title('<h1 class="page-title">', '</h1>');
				the_archive_description('<div class="archive-description">', '</div>');
				?>
			</header><!-- .page-header -->
			<div class="blog-container">
				<?php
				/* Start the Loop */
				while (have_posts()) :
					the_post();

					/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
					get_template_part('template-parts/content', 'blog');
				endwhile;
				?>
			</div>
			<?php
			/*the_posts_navigation();*/
			the_posts_pagination(
				array(
					'prev_text'          => __('<small><< Previous</small>') . '<span class="screen-reader-text">' . __("Previous page", "wileecoder") . '</span>',
					'next_text'          => '<span class="screen-reader-text">' . __("Next page", "wileecoder") . '</span>' . __('<small>Next >></small>'),
					'mid_size'			 => 1,
				)
			);


		else :

			get_template_part('template-parts/content', 'none');

		endif;
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();

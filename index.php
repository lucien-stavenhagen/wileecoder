<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wileecoder
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<div class="blog-container">
		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				/* LS custom
				* get the content-blog.php template part
				* for the blog summary. It only shows
				* the_excerpt.
				* original line:
				* get_template_part( 'template-parts/content', get_post_type() );
				*/
				get_template_part( 'template-parts/content', 'blog' );
				
			endwhile;
			?>
		</div>
		<?php
			/*the_posts_navigation();*/
			the_posts_pagination(
				array(
					'prev_text'          => __('<small><< Previous</small>') . '<span class="screen-reader-text">' . __("Previous page", "wileecoder") . '</span>',
					'next_text'          => '<span class="screen-reader-text">' . __("Next page", "wileecoder") . '</span>' . __( '<small>Next >></small>'),
					'mid_size'			 => 1,
				)
			);

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();

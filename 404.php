<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package wileecoder
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<section class="error-404 not-found">
			<div class="not-found-info">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e('Four-Oh-Four: That page can&rsquo;t be found.', 'wileecoder'); ?></h1>
			</header><!-- .page-header -->
			</div>
			<div class="page-content">
				<!--LS custom-->
				<div class="not-found-info">
					<p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'wileecoder'); ?></p>
				</div>
				<div class="not-found-info">
					<?php
					get_search_form();
					?>
				</div>
				<div class="not-found-info">
					<?php
					the_widget('WP_Widget_Recent_Posts');
					?>
				</div>
				<div class="not-found-info">
					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'wileecoder'); ?></h2>
						<ul>
							<?php
							wp_list_categories(array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							));
							?>
						</ul>
					</div>
				</div>
				<div class="not-found-info">
					<?php
					/* translators: %1$s: smiley */
					$wileecoder_archive_content = '<p>' . sprintf(esc_html__('Try looking in the monthly archives. %1$s', 'wileecoder'), convert_smilies(':)')) . '</p>';
					the_widget('WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$wileecoder_archive_content");

					the_widget('WP_Widget_Tag_Cloud');
					?>
				</div>
		</section><!-- .error-404 -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();

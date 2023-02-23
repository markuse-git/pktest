<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Inspiro
 * @subpackage Inspiro_Lite
 * @since Inspiro 1.0.0
 * @version 1.0.0
 */

get_header(); ?>

<div class="inner-wrap">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php
				// the_archive_title( '<h1 class="page-title">', '</h1>' );
                echo '<h1 class="page-title">In den Medien</h1>';
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
	<?php endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :
			?>
			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/post/clipping-content', get_post_format() );
			endwhile;

			the_posts_pagination(
				array(
					'prev_next' => false,
				)
			);
		else :
			get_template_part( 'template-parts/post/content', 'none' );
		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .inner-wrap -->

<?php
get_footer();

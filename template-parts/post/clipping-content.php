<?php

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'template-parts/post/article/clipping-archive-header' ); ?>

	<!-- Im Archive mit Excerpt -->
	<?php if ( ! is_single() && 'excerpt' === inspiro_get_theme_mod( 'display_content' ) ) : ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	<?php endif ?>
	
	<?php
	if ( is_single() && 'side-right' === inspiro_get_theme_mod( 'layout_single_post' ) && is_active_sidebar( 'blog-sidebar' ) ) {
		echo '<div class="entry-wrapper">';
	}
	?>

	<?php if ( is_single() || ( ! is_single() && 'full-content' === inspiro_get_theme_mod( 'display_content' ) ) ) : ?>
		<div class="entry-content">

            <!-- Ausgabe der Custom Fields für Clippins -->
			<p><h5><a href='<?php the_field('zum_beitrag');?>'><?php the_field('publication');?></a> | <?php the_field('publication_date');?></h5></p>
			
            <?php
			the_content(
				sprintf(
					/* translators: %s: Post title. */
					__( 'Read more<span class="screen-reader-text"> "%s"</span>', 'inspiro' ),
					get_the_title()
				)
			);

			wp_link_pages(
				array(
					'before'      => '<div class="page-links">' . __( 'Pages:', 'inspiro' ),
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				)
			);
			?>
		</div><!-- .entry-content -->
	<?php endif ?>

	<?php if ( is_single() && 'side-right' === inspiro_get_theme_mod( 'layout_single_post' ) && is_active_sidebar( 'blog-sidebar' ) ) : ?>

		<aside id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'blog-sidebar' ); ?>
		</aside>

		</div><!-- .entry-wrapper -->

		<div class="clear"></div>

	<?php endif ?>

	<?php
	if ( is_single() ) {
		inspiro_entry_footer();
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php

 $cover_height = inspiro_get_theme_mod( 'cover-size' );

if ( is_sticky() && is_home() ) {
	echo inspiro_get_theme_svg( 'thumb-tack' );
}
?>


<?php
/*
 * If a regular post or page, and not the front page, show the featured image as header cover image.
 */
if ( ( is_single() || ( is_page() && ! inspiro_is_frontpage() ) ) && has_post_thumbnail( get_the_ID() ) ) {
	echo '<div class="entry-cover-image '.$cover_height.'">';
	echo '<div class="single-featured-image-header">';
	echo get_the_post_thumbnail( get_the_ID(), 'inspiro-featured-image' );
	echo '</div><!-- .single-featured-image-header -->';
}
?>

<header class="entry-header">

	<?php
	if ( ( is_single() || ( is_page() && ! inspiro_is_frontpage() ) ) ) {
		echo '<div class="inner-wrap">';
	}

	if ( is_single() ) {
		the_title( '<h1 class="entry-title">', '</h1>' );
	} elseif ( is_front_page() && is_home() ) {
		the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
	} else {
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_field('zum_beitrag') ) . '" rel="bookmark">', '</a></h2>' );
        // Custom Fields für Clippings
        ?><h5><?php the_field('publication');?> | <?php the_field('publication_date');?></h5>
        <?php
	}

	if ( 'post' === get_post_type() ) {
		echo '<div class="entry-meta">';
		if ( is_single() ) {
			inspiro_single_entry_meta();
		} else {
			echo inspiro_entry_meta(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		};
		echo '</div><!-- .entry-meta -->';
	}

	if ( ( is_single() || ( is_page() && ! inspiro_is_frontpage() ) ) ) {
		echo '</div><!-- .inner-wrap -->';
	}
	?>
</header><!-- .entry-header -->

<?php
if ( ( is_single() || ( is_page() && ! inspiro_is_frontpage() ) ) && has_post_thumbnail( get_the_ID() ) ) {
	echo '</div><!-- .entry-cover-image -->';
}
?>

<?php
get_header();

/* Start the Loop */
while (have_posts()) :
	the_post();
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
			<h1><?php echo the_title(); ?></h1>
			<span>
				<?php echo get_post_meta($post->ID, 'location_address', true); ?><br />
				<?php echo get_post_meta($post->ID, 'location_city', true); ?>,
				<?php echo get_post_meta($post->ID, 'location_state', true); ?>
				<?php echo get_post_meta($post->ID, 'location_postal', true); ?><br />
			</span>
			<?php echo (has_post_thumbnail()) ? get_the_post_thumbnail() : '<img src="https://dummyimage.com/300" />'; ?>
			<?php echo the_content(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer default-max-width">
		</footer><!-- .entry-footer -->

	</article><!-- #post-<?php the_ID(); ?> -->
<?php
endwhile; // End of the loop.

get_footer();

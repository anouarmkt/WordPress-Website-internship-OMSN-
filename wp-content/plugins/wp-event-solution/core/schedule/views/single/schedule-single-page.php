<?php get_header();?>

<?php
$options       = get_option( 'etn_event_general_options' );
$container_cls = isset( $options['single_post_container_width_cls'] )
? $options['single_post_container_width_cls']
: '';
?>
<div class="etn-es-events-page-container etn-container <?php echo esc_attr( $container_cls ); ?>">
	<?php
	while ( have_posts() ):
		the_post();
		require \Wpeventin::plugin_dir() . 'core/schedule/views/parts/schedule.php';
	endwhile;
	?>
</div>

<?php get_footer();?>
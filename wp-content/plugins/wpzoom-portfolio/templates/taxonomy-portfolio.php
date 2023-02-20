<?php
/**
 * The template for displaying archive pages
 * 
 */

get_header();

$description  = get_the_archive_description();
$current_term = get_queried_object();

$taxonomy_options = get_option( 'wpzoom-portfolio-settings' );

$primaryColor   = !empty( $taxonomy_options['wpzoom_portfolio_settings_primary_color'] ) ? $taxonomy_options['wpzoom_portfolio_settings_primary_color'] : '#0BB4AA';
$secondaryColor = !empty( $taxonomy_options['wpzoom_portfolio_settings_secondary_color'] ) ? $taxonomy_options['wpzoom_portfolio_settings_secondary_color'] : '#000';

$layout        = isset( $taxonomy_options['wpzoom_portfolio_settings_taxonomy_layout'] ) ? $taxonomy_options['wpzoom_portfolio_settings_taxonomy_layout'] : 'grid';
$postsAmount = isset( $taxonomy_options['wpzoom_portfolio_settings_number_posts'] ) ? $taxonomy_options['wpzoom_portfolio_settings_number_posts'] : 9;
$columnsAmount = isset( $taxonomy_options['wpzoom_portfolio_settings_number_columns'] ) ? $taxonomy_options['wpzoom_portfolio_settings_number_columns'] : 3;
$columnsGap    = isset( $taxonomy_options['wpzoom_portfolio_settings_columns_gap'] ) ? $taxonomy_options['wpzoom_portfolio_settings_columns_gap'] : 0;

$showThumbnail = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_show_thumbnail'] ? true : false );
$thumbnailSize = isset( $taxonomy_options['wpzoom_portfolio_settings_taxonomy_img_size'] ) ? $taxonomy_options['wpzoom_portfolio_settings_taxonomy_img_size'] : 'portfolio_item-thumbnail';

$showAuthor    = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_show_author'] ? true : false );
$showDate      = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_show_date'] ? true : false );
$showExcerpt   = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_show_excerpt'] ? true : false );
$showReadMore  = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_show_read_more'] ? true : false );
$readMoreLabel = !empty( $taxonomy_options['wpzoom_portfolio_settings_readmore_label'] ) ? esc_html( $taxonomy_options['wpzoom_portfolio_settings_readmore_label'] ) : esc_html__( 'Read More', 'wpzoom-portfolio' );

$lightbox        = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_lightbox'] ? true : false );
$lightboxCaption = ( '1' === $taxonomy_options['wpzoom_portfolio_settings_lightbox_caption'] ? true : false );

$atts = array(
	'align'                     => '',
	'amount'                    => $postsAmount,
	'alwaysPlayBackgroundVideo' => false,
	'categories'                => array($current_term->term_id),
	'columnsAmount'             => $columnsAmount,
	'columnsGap'                => $columnsGap,
	'excerptLength'             => 20,
	'layout'                    => $layout,
	'lightbox'                  => $lightbox,
	'lightboxCaption'           => $lightboxCaption,
	'order'                     => 'desc',
	'orderBy'                   => 'date',
	'readMoreLabel'             => $readMoreLabel,
	'showAuthor'                => $showAuthor,
	'showBackgroundVideo'       => true,
	'showCategoryFilter'        => false,
	'showDate'                  => $showDate,
	'showExcerpt'               => $showExcerpt ,
	'showReadMore'              => $showReadMore,
	'showThumbnail'             => $showThumbnail,
	'showViewAll'               => false,
	'source'                    => 'portfolio_item',
	'thumbnailSize'             => $thumbnailSize,
	'viewAllLabel'              => 'View All',
	'viewAllLink'               => '',
	'primaryColor'              => $primaryColor,
	'secondaryColor'            => $secondaryColor
);


$content = '';

$block_portfolio = new WPZOOM_Blocks_Portfolio;
$block_portfolio_render = $block_portfolio->render( $atts, $content );

?>

<?php if ( have_posts() ) : ?>

	<header class="page-header alignwide">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->

	<?php 
		printf( 
			'<div class="wpzoom-block-portfolio-taxonomy">%1$s</div>',
			$block_portfolio_render	
		);
	?>

	<?php else : ?>
		<?php get_template_part( 'template-parts/content/content-none' ); ?>
	<?php endif; ?>

<?php get_footer(); ?>
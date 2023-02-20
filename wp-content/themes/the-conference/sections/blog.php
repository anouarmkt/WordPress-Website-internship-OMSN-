<?php
/**
 * Blog Section
 * 
 * @package The Conference
 */

$btitle             = get_theme_mod( 'blog_section_title', __( 'Recent Posts', 'the-conference' ) );
$sub_title          = get_theme_mod( 'blog_section_subtitle', __( 'See what other people are saying about us', 'the-conference' ) );
$blog               = get_option( 'page_for_posts' );
$label              = get_theme_mod( 'blog_view_all', __( 'SEE ALL POSTS', 'the-conference' ) );
$hide_cat_single    = get_theme_mod( 'ed_category', false );
$hide_post_date     = get_theme_mod( 'ed_post_date', false );
$hide_comment_count = get_theme_mod( 'ed_post_comment_count', false );

$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true
);

$qry = new WP_Query( $args );

if( $btitle || $sub_title || $qry->have_posts() ){ ?>

<section id="blog_section" class="blog-section">
	<div class="container">
        
        <?php 
            if( $btitle ) echo '<h2 class="section-title">' . esc_html( $btitle ) . '</h2>';
            if( $sub_title ) echo '<div class="section-desc">' . wpautop( wp_kses_post( $sub_title ) ) . '</div>'; 
            
            if( $qry->have_posts() ){ ?>
            <div class="article-wrap">
    			<?php while( $qry->have_posts() ){
                    $qry->the_post(); ?>
                    <article class="post">
                        <figure class="post-thumbnail">
                            <?php if( ! $hide_cat_single ) the_conference_category(); ?>
                            <a href="<?php the_permalink(); ?>">
                            <?php
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( 'the-conference-blog', array( 'itemprop' => 'image' ) );
                                }else{ 
                                    the_conference_fallback_image( 'the-conference-blog' );
                                }                          
                            ?>
                            </a>
                        </figure>
                        <header class="entry-header">
                            <?php if( ! $hide_post_date || ! $hide_comment_count ){ ?>
                                <div class="entry-meta">
                                <?php 
                                    if( ! $hide_post_date ) the_conference_posted_on();
                                    if( ! $hide_comment_count ) the_conference_comment_count();
                                ?>
                                </div>
                             <?php } ?>
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        </header>
                    </article> <!-- .post -->
        			<?php 
                }
                wp_reset_postdata();
                ?>
    		</div>
    		
            <?php if( $blog && $label ){ ?>
                <div class="btn-wrap">
        			<a href="<?php the_permalink( $blog ); ?>" class="btn-filled"><?php echo esc_html( $label ); ?></a>
        		</div>
            <?php } ?>
        
        <?php } ?>
	</div>
</section>
<?php 
}
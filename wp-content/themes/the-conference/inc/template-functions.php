<?php
/**
 * The Conference Template Functions which enhance the theme by hooking into WordPress
 *
 * @package The Conference
 */

if( ! function_exists( 'the_conference_doctype' ) ) :
/**
 * Doctype Declaration
*/
function the_conference_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'the_conference_doctype', 'the_conference_doctype' );

if( ! function_exists( 'the_conference_head' ) ) :
/**
 * Before wp_head 
*/
function the_conference_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'the_conference_before_wp_head', 'the_conference_head' );

if( ! function_exists( 'the_conference_page_start' ) ) :
/**
 * Page Start
*/
function the_conference_page_start(){ ?>
      <button class="toggle-btn" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle"><span class="bar"></span><span class="bar"></span><span class="bar"></span></button>
      
    <div class="nav-wrap mobile-menu-wrapper">
        <?php the_conference_primary_mobile_nagivation(); ?>
    </div>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#acc-content"><?php esc_html_e( 'Skip to content (Press Enter)', 'the-conference' ); ?></a>
    <?php
}
endif;
add_action( 'the_conference_before_header', 'the_conference_page_start', 20 );

if( ! function_exists( 'the_conference_header' ) ) :
/**
 * Header Start
*/
function the_conference_header(){ 
   ?>
    <header class="site-header" itemscope itemtype="https://schema.org/WPHeader">
        <div class="container">
            <?php the_conference_site_branding(); ?>
            <div class="nav-wrap">
                <?php 
                    the_conference_primary_nagivation(); 
                    the_conference_custom_header_link();
                ?>
            </div>
        </div>
    </header><!-- .site-header -->
   <?php
}
endif;
add_action( 'the_conference_header', 'the_conference_header', 20 );

if( ! function_exists( 'the_conference_banner' ) ) :
/**
 * Banner 
*/
function the_conference_banner(){
    $ed_banner          = get_theme_mod( 'ed_banner_section', 'static_banner' );
    $banner_title       = get_theme_mod( 'banner_title', __( 'Lepiza Announces New Design', 'the-conference' ) );
    $banner_subtitle    = get_theme_mod( 'banner_subtitle', __( 'October 10 & 11 - Berlin, Germany', 'the-conference' ) );
    $banner_label_one   = get_theme_mod( 'banner_label_one', __( 'VIEW SCHEDULE', 'the-conference' ) );
    $banner_link_one    = get_theme_mod( 'banner_link_one', '#' );
    $banner_label_two   = get_theme_mod( 'banner_label_two', __( 'BUY TICKET NOW', 'the-conference' ) );
    $banner_link_two    = get_theme_mod( 'banner_link_two', '#' );
    $ed_banner_timer    = get_theme_mod( 'ed_banner_event_timer', true );
    $event_time         = get_theme_mod( 'banner_event_timer', '2020-08-20' );
    $event_datetime_obj = new DateTime( $event_time );
    $today_datetime_obj = new DateTime( date('Y-m-d') );

    if( is_front_page() && ! is_home() && ( $ed_banner == 'static_banner' ) && has_custom_header() ){ ?>
        <div id="banner_section" class="site-banner<?php if( has_header_video() ) echo esc_attr( ' video-banner' ); ?>">
            <?php 
                echo '<div class="item">';

                the_custom_header_markup(); 

                echo '<span class="scroll-down"></span>';
                
                if( $ed_banner == 'static_banner' && ( $banner_title || $banner_subtitle || ( $banner_label_one && $banner_link_one ) || ( $banner_label_two && $banner_link_two ) ) ){
                    echo '<div class="banner-caption static-banner"><div class="container">';
                    if( $banner_title ) echo '<h2 class="banner-title wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s">' . esc_html( $banner_title ) . '</h2>';
                    if( $banner_subtitle ) echo '<div class="banner-desc wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">' . wpautop( wp_kses_post( $banner_subtitle ) ) . '</div>';

                    if( ( $banner_label_one && $banner_link_one ) || ( $banner_label_two && $banner_link_two ) ){
                        echo '<div class="btn-wrap">';
                        if( $banner_label_one && $banner_link_one ) echo '<a href="' . esc_url( $banner_link_one ) . '" class="btn-transparent wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.5s">' . esc_html( $banner_label_one ) . '</a>';
                        if( $banner_label_two && $banner_link_two ) echo '<a href="' . esc_url( $banner_link_two ) . '" class="btn-filled wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">' . esc_html( $banner_label_two ) . '</a>';
                        echo '</div>';
                    } 
                    
                    if( $ed_banner_timer ){
                        if( $event_datetime_obj > $today_datetime_obj ){ ?>
                            <div id="bannerClock" class="banner-countdown wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
                                <div class="countdown-wrap">
                                    <span class="days"></span>
                                    <div class="smalltext"><?php esc_html_e( 'Days', 'the-conference' ); ?></div>
                                </div>
                                <div class="countdown-wrap">
                                    <span class="hours"></span>
                                    <div class="smalltext"><?php esc_html_e( 'Hours', 'the-conference' ); ?></div>
                                </div>
                                <div class="countdown-wrap">
                                    <span class="minutes"></span>
                                    <div class="smalltext"><?php esc_html_e( 'Minutes', 'the-conference' ); ?></div>
                                </div>
                                <div class="countdown-wrap">
                                    <span class="seconds"></span>
                                    <div class="smalltext"><?php esc_html_e( 'Seconds', 'the-conference' ); ?></div>
                                </div>
                            </div>
                        <?php
                        } elseif( is_user_logged_in() ) {
                            echo '<div class="banner-countdown"><p>'. esc_html__( 'Event Expired', 'the-conference' ) .'</p></div>';
                        }
                    }
                    echo '</div></div>';
                }
                echo '</div>';
                
            ?>
        </div>
    <?php 
    }
}
endif;
add_action( 'the_conference_after_header', 'the_conference_banner' );

if( ! function_exists( 'the_conference_content_start' ) ) :
/**
 * Content Start
 * 
*/
function the_conference_content_start(){
    echo '<div id="acc-content">';
    $home_sections = the_conference_get_home_sections();

    if( is_front_page() && ! is_home() ){
        if( empty( $home_sections ) ){
            echo '<div id="content" class="site-content"><div class="container">';
        }
    }else{ ?>     
        <div id="content" class="site-content">
        <?php the_conference_header_banner(); ?>
            <div class="container">
        <?php
    }   
}
endif;
add_action( 'the_conference_content', 'the_conference_content_start' );

if( ! function_exists( 'the_conference_entry_header' ) ) :
/**
 * Entry Header
*/
function the_conference_entry_header(){
    $blog_layout = get_theme_mod( 'blog_page_layout', 'classic-view' );

    if( ! ( ( is_home() && 'classic-view' == $blog_layout ) || ( is_single() || is_page() ) ) ){
        echo '<div class="post-content-wrap">';
    }
    ?>
    <header class="entry-header">
        <?php 
            $hide_post_date     = get_theme_mod( 'ed_post_date', false );
            $hide_comment_count = get_theme_mod( 'ed_post_comment_count', false );
        ?>
        <h2 class="entry-title" itemprop="headline">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        
        <?php if( ! $hide_comment_count || ! $hide_post_date ){ ?>
            <div class="entry-meta">
                <?php 
                    if( ! $hide_post_date ) the_conference_posted_on();
                    if( ! $hide_comment_count ) the_conference_comment_count();
                ?>
            </div>
        <?php } ?>
	</header>         
    <?php    
}
endif;
add_action( 'the_conference_before_posts_entry_content', 'the_conference_entry_header', 20 );

if ( ! function_exists( 'the_conference_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function the_conference_post_thumbnail() {
    $hide_category  = get_theme_mod( 'ed_category', false );
    $home_layout    = get_theme_mod( 'blog_page_layout', 'classic-view' );
    $sidebar_layout = the_conference_sidebar_layout( true );

    if( is_home() || is_archive() || is_search() ){
        echo '<figure class="post-thumbnail">';
        
        if( ! $hide_category ) the_conference_category();

        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
            if( is_home() ){
                $thumbnail_size = 'the-conference-blog';
                if( 'full-width' == $sidebar_layout &&  'classic-view' == $home_layout ){
                    $thumbnail_size = 'the-conference-blog-fullwidth';
                }

                if( has_post_thumbnail() ){
                    the_post_thumbnail( $thumbnail_size, array( 'itemprop' => 'image' ) );    
                }else{
                    the_conference_fallback_image( $thumbnail_size );
                }

            }else{
                if( has_post_thumbnail() ){
                    the_post_thumbnail( 'the-conference-blog', array( 'itemprop' => 'image' ) );    
                }else{
                    the_conference_fallback_image( 'the-conference-blog' );
                }
            }
        echo '</a>';

        echo '</figure>';
    }
}
endif;
add_action( 'the_conference_before_posts_entry_content', 'the_conference_post_thumbnail', 15 );

if( ! function_exists( 'the_conference_entry_content' ) ) :
/**
 * Entry Content
*/
function the_conference_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); ?>
    <div class="entry-content" itemprop="text">
		<?php
			if( is_singular() || ! $ed_excerpt || ( get_post_format() != false ) ){
                the_content();    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'the-conference' ),
    				'after'  => '</div>',
    			) );
            }else{
                the_excerpt();
            }
		?>
	</div><!-- .entry-content -->
    <?php
}
endif;
add_action( 'the_conference_page_entry_content', 'the_conference_entry_content', 15 );
add_action( 'the_conference_post_entry_content', 'the_conference_entry_content', 15 );
add_action( 'the_conference_posts_entry_content', 'the_conference_entry_content', 15 );

if( ! function_exists( 'the_conference_entry_footer' ) ) :
/**
 * Entry Footer
*/
function the_conference_entry_footer(){ 
    $blog_layout = get_theme_mod( 'blog_page_layout', 'classic-view' );
    $readmore = get_theme_mod( 'read_more_text', __( 'CONTINUE READING', 'the-conference' ) ); ?>
	<footer class="entry-footer">
		<?php
			if( is_single() && 'post' == get_post_type() ){
			    the_conference_tag();
			}
            
            if( is_home() || is_archive() || is_search() ){
                echo '<a href="' . esc_url( get_the_permalink() ) . '" class="btn-transparent">' . esc_html( $readmore ) . '<i class="fas fa-long-arrow-alt-right"></i></a>';    
            }
            
            if( get_edit_post_link() ){
                edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'the-conference' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
            }
		?>
	</footer><!-- .entry-footer -->
	<?php 
    if( ! ( ( is_home() && 'classic-view' == $blog_layout ) || ( is_single() || is_page() ) ) ){
        echo '</div><!-- .post-content-wrap -->';
    }
}
endif;
add_action( 'the_conference_page_entry_content', 'the_conference_entry_footer', 20 );
add_action( 'the_conference_post_entry_content', 'the_conference_entry_footer', 20 );
add_action( 'the_conference_posts_entry_content', 'the_conference_entry_footer', 20 );

if( ! function_exists( 'the_conference_navigation' ) ) :
/**
 * Navigation
*/
function the_conference_navigation(){
    if( is_single() ){
        $previous = get_previous_post_link(
    		'<div class="nav-previous nav-holder">%link</div>',
    		'<span class="meta-nav"><i class="fa fa-long-arrow-alt-left"></i>' . esc_html__( 'Previous Article', 'the-conference' ) . '</span><span class="post-title">%title</span>',
    		false,
    		'',
    		'category'
    	);
    
    	$next = get_next_post_link(
    		'<div class="nav-next nav-holder">%link</div>',
    		'<span class="meta-nav"><i class="fa fa-long-arrow-alt-right"></i>' . esc_html__( 'Next Article', 'the-conference' ) . '</span><span class="post-title">%title</span>',
    		false,
    		'',
    		'category'
    	); 
        
        if( $previous || $next ){?>            
            <nav class="navigation post-navigation" role="navigation">
    			<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'the-conference' ); ?></h2>
    			<div class="nav-links">
    				<?php
                        if( $previous ) echo $previous;
                        if( $next ) echo $next;
                    ?>
    			</div>
    		</nav>        
            <?php
        }
    }else{                    
        the_posts_navigation();
    }
}
endif;
add_action( 'the_conference_after_post_content', 'the_conference_navigation', 15 );
add_action( 'the_conference_after_posts_content', 'the_conference_navigation' );

if( ! function_exists( 'the_conference_author' ) ) :
/**
 * Author Section
*/
function the_conference_author(){ 
    $ed_author   = get_theme_mod( 'ed_author', false );
    $author_name = get_the_author_meta( 'display_name' );
    $author_bio  = get_the_author_meta( 'description' );

    if( ! $ed_author && $author_bio ){ ?>
        <div class="about-author">
            <figure class="author-img">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
            </figure>
            <div class="author-content-wrap">
                <?php 
                    if( $author_name ){ 
                        echo '<h3 class="author-name"><span class="title-wrap">'. esc_html( $author_name ) .'</span></h3>';
                    }

                    if( $author_bio ){
                        echo '<div class="author-info">'. wpautop( wp_kses_post( $author_bio ) ) .'</div>';
                    }
                ?>
            </div>
        </div> <!-- .about-author -->
    <?php
    }
}
endif;
add_action( 'the_conference_after_post_content', 'the_conference_author', 25 );

if( ! function_exists( 'the_conference_related_posts' ) ) :
/**
 * Related Posts 
*/
function the_conference_related_posts(){
    $ed_related_post = get_theme_mod( 'ed_related', true );

    if( $ed_related_post ){
        the_conference_get_posts_list( 'related' ); 
    }   
}
endif;                                                                               
add_action( 'the_conference_after_post_content', 'the_conference_related_posts', 35 );

if( ! function_exists( 'the_conference_latest_posts' ) ) :
/**
 * Latest Posts
*/
function the_conference_latest_posts(){ 
    the_conference_get_posts_list( 'latest' );
}
endif;
add_action( 'the_conference_latest_posts', 'the_conference_latest_posts' );

if( ! function_exists( 'the_conference_comment' ) ) :
/**
 * Comments Template 
*/
function the_conference_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if( get_theme_mod( 'ed_comments', true ) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
}
endif;
add_action( 'the_conference_after_post_content', 'the_conference_comment', 45 );
add_action( 'the_conference_after_page_content', 'the_conference_comment' );

if( ! function_exists( 'the_conference_content_end' ) ) :
/**
 * Content End
*/
function the_conference_content_end(){
    $home_sections = the_conference_get_home_sections();

    if( is_front_page() && ! is_home() ){
        if( empty( $home_sections ) ){
            echo '</div></div>';
        }
    }else{ ?>    
            </div><!-- .container -->  
        </div><!-- .site-content -->
    <?php
    }
}
endif;
add_action( 'the_conference_before_footer', 'the_conference_content_end', 20 );

if( ! function_exists( 'the_conference_newsletter_section' ) ) :
/**
 * Newsletter Section
*/
function the_conference_newsletter_section(){ 
    $ed_newsletter = get_theme_mod( 'ed_newsletter', false );
    $ed_gradient   = get_theme_mod( 'ed_newsletter_gradient', true );
    $newsletter    = get_theme_mod( 'newsletter_shortcode' );
    $class         = $ed_gradient ? ' gradient-enabled' : '';

    if( $ed_newsletter && $newsletter ){ ?>
        <section id="newsletter_section" class="newsletter-section<?php echo esc_attr( $class ); ?>">
            <div class="wrapper">
                <?php echo do_shortcode( wp_kses_post( $newsletter ) ); ?>
            </div>
        </section> <!-- .newsletter-section -->
    <?php    
    }
}
endif;
add_action( 'the_conference_before_footer', 'the_conference_newsletter_section', 25 );

if( ! function_exists( 'the_conference_footer_start' ) ) :
/**
 * Footer Start
*/
function the_conference_footer_start(){
    ?>
    <div class="overlay"></div>
    <footer id="colophon" class="site-footer" itemscope itemtype="https://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'the_conference_footer', 'the_conference_footer_start', 20 );

if( ! function_exists( 'the_conference_footer_top' ) ) :
/**
 * Footer Top
*/
function the_conference_footer_top(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }
                 
    if( $active_sidebars ){ ?>
        <div class="top-footer">
    		<div class="container">
    			<div class="grid column-<?php echo esc_attr( $sidebar_count ); ?>">
                <?php foreach( $active_sidebars as $active ){ ?>
    				<div class="col">
    				   <?php dynamic_sidebar( $active ); ?>	
    				</div>
                <?php } ?>
                </div>
    		</div>
    	</div>
        <?php 
    }
}
endif;
add_action( 'the_conference_footer', 'the_conference_footer_top', 30 );

if( ! function_exists( 'the_conference_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function the_conference_footer_bottom(){ ?>
    <div class="bottom-footer">
		<div class="container">
			<div class="site-info">            
            <?php
                the_conference_get_footer_copyright();
                the_conference_ed_author_link();
                the_conference_ed_wp_link();
                
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
		</div>
	</div>
    <?php
}
endif;
add_action( 'the_conference_footer', 'the_conference_footer_bottom', 40 );

if( ! function_exists( 'the_conference_footer_end' ) ) :
/**
 * Footer End 
*/
function the_conference_footer_end(){ ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'the_conference_footer', 'the_conference_footer_end', 50 );

if( ! function_exists( 'the_conference_page_end' ) ) :
/**
 * Page End
*/
function the_conference_page_end(){ ?>
    </div><!-- #acc-content -->
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'the_conference_after_footer', 'the_conference_page_end', 20 );


if( ! function_exists( 'the_conference_post_count' ) ) :
/**
 * Post counts in search and archive page.
*/
function the_conference_post_count(){
    if( is_search() || is_archive() || is_author() ){
        global $wp_query;
        $found_posts  = $wp_query->found_posts;
        $paged        = get_query_var( 'paged', 0 );
        $visible_post = get_option( 'posts_per_page' );
        $paged_index  = $found_posts / $visible_post;

        if( $found_posts > 0 ){
            echo '<div class="post-count">';
            if( $found_posts > $visible_post ){
                if( $paged == 0 ){
                    $start_post = 1;
                    $end_post = $visible_post;
                }elseif( $paged < $paged_index ){
                    $start_post = ( ( $paged - 1 ) * $visible_post ) + 1;
                    $end_post = $paged * $visible_post;
                }else{
                    $start_post = ( ( $paged - 1 ) * $visible_post ) + 1;
                    $end_post = ( $paged - 1 ) * $visible_post + ( $found_posts - ( ( $paged - 1 ) * $visible_post ) );
                }            
                printf( esc_html__( 'Showing: %1$s - %2$s of %3$s RESULTS', 'the-conference' ), number_format_i18n( $start_post ), number_format_i18n( $end_post ), number_format_i18n( $found_posts ) );
            }else{
                /* translators: 1: found posts. */
                printf( _nx( '%s RESULT', '%s RESULTS', $found_posts, 'found posts', 'the-conference' ), number_format_i18n( $found_posts ) );
            }
            echo '</div>';
        }
    }        
}
endif;
add_action( 'the_conference_before_posts_content', 'the_conference_post_count' );
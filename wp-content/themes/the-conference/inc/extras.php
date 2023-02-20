<?php
/**
 * The Conference Standalone Functions.
 *
 * @package The Conference
 */

if ( ! function_exists( 'the_conference_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function the_conference_posted_on() {
	$ed_updated_post_date = get_theme_mod( 'ed_post_update_date', true );
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		if( $ed_updated_post_date ){
            $time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time></time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
		}else{
            $time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';  
		}        
	}else{
	   $time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';   
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    $posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	
	echo '<span class="posted-on"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><defs><style>.clk{fill:#999;}</style></defs><g transform="translate(-2 -2)"><g transform="translate(2 2)"><path class="clk" d="M11,2a9,9,0,1,0,9,9A9.026,9.026,0,0,0,11,2Zm0,16.2A7.2,7.2,0,1,1,18.2,11,7.21,7.21,0,0,1,11,18.2Z" transform="translate(-2 -2)"/><path class="clk" d="M12.35,7H11v5.4l4.68,2.88.72-1.17-4.05-2.43Z" transform="translate(-2.9 -2.5)"/></g></g></svg>' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'the_conference_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
function the_conference_posted_by( $author_id = '' ) {

    if( $author_id ){
        $author_url = get_author_posts_url( $author_id ); 
        $author_name = get_the_author_meta( 'display_name', $author_id );
    }else{
        $author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
        $author_name = get_the_author();
    }

	$byline = sprintf(
		'<span class="author" itemprop="name"><a class="url fn n" href="' . esc_url( $author_url ) . '" itemprop="url">' . esc_html( $author_name ) . '</a></span>' 
    );

	echo '<span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19"><defs><style>.auta{fill:none;}.auta,.autb{stroke:rgba(0,0,0,0);}.autb{fill:#ccc6c8;}</style></defs><g transform="translate(0.5 0.5)"><path class="auta" d="M0,0H18V18H0Z"/><g transform="translate(1.5 1.5)"><path class="autb" d="M9.5,2A7.5,7.5,0,1,0,17,9.5,7.5,7.5,0,0,0,9.5,2ZM5.8,14.21c.322-.675,2.287-1.335,3.7-1.335s3.382.66,3.7,1.335a5.944,5.944,0,0,1-7.395,0Zm8.468-1.088c-1.073-1.3-3.675-1.747-4.77-1.747s-3.7.443-4.77,1.747a6,6,0,1,1,9.54,0Z" transform="translate(-2 -2)"/><path class="autb" d="M11.125,6A2.625,2.625,0,1,0,13.75,8.625,2.618,2.618,0,0,0,11.125,6Zm0,3.75A1.125,1.125,0,1,1,12.25,8.625,1.123,1.123,0,0,1,11.125,9.75Z" transform="translate(-3.625 -3)"/></g></g></svg>' . $byline . '</span>';
}
endif;

if( ! function_exists( 'the_conference_comment_count' ) ) :
/**
 * Comment Count
*/
function the_conference_comment_count(){
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comment-box">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><defs><style>.cmt{fill:#999;}</style></defs><path class="cmt" d="M15.5,2H3.5A1.5,1.5,0,0,0,2,3.5V17l3-3H15.5A1.5,1.5,0,0,0,17,12.5v-9A1.5,1.5,0,0,0,15.5,2Zm0,10.5H5L3.5,14V3.5h12Z" transform="translate(-2 -2)"/></svg>';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'No Comment<span class="screen-reader-text"> on %s</span>', 'the-conference' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			), 
            __( '1 Comment', 'the-conference' ), 
            __( '% Comments', 'the-conference' ) 
		);
		echo '</span>';
	}    
}
endif;

if ( ! function_exists( 'the_conference_category' ) ) :
/**
 * Prints categories
 */
function the_conference_category( $section = '' ){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category();
		if ( $categories_list ) {
            echo '<span class="category">';
                foreach ( $categories_list as $category ) {
                    $cat_name = $category->cat_name;
                    $cat_url = get_term_link( $category->term_id );

                    if( $cat_name && $cat_url ){
                        echo '<a href="'. esc_url( $cat_url ) .'">'. esc_html( $cat_name ) .'</a>';
                    }
                }
            echo '</span>';
		}
	}
}
endif;

if ( ! function_exists( 'the_conference_tag' ) ) :
/**
 * Prints tags
 */
function the_conference_tag(){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$post_tags = get_the_tags();
		if ( $post_tags ) {
            echo '<span class="cat-tags"><h5>'. esc_html__( 'TAGS:', 'the-conference' ) .'</h5>';
            foreach( $post_tags as $tag ) {
                echo '<a href="'. esc_url( get_tag_link( $tag->term_id ) ) .'">'. esc_html( $tag->name ) .'</a>'; 
            }
            echo '</span>';
		}
	}
}
endif;

if( ! function_exists( 'the_conference_site_branding' ) ) :
/**
 * Site Branding
*/
function the_conference_site_branding(){ 
    $display_header_text = get_theme_mod( 'header_text', 1 );
    $site_title          = get_bloginfo( 'name', 'display' );
    $description         = get_bloginfo( 'description', 'display' );

    if( ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) && $display_header_text && ( ! empty( $site_title ) || ! empty(  $description  ) ) ){
        $branding_class = ' logo-with-site-identity';                                                                               
    } else {
        $branding_class = '';
    }
    ?>
    <div class="site-branding<?php echo esc_attr( $branding_class ); ?>" itemscope itemtype="https://schema.org/Organization">
		<?php 
            if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                echo '<div class="site-logo">';
                the_custom_logo();
                echo '</div><!-- .site-logo -->';
            } 
            
            echo '<div class="site-title-wrap">';

            if( is_front_page() ){ ?>
                <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                <?php 
            }else{ ?>
                <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
            <?php
            }

            $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ){ ?>
                <p class="site-description" itemprop="description"><?php echo $description; ?></p>
            <?php
            }
        ?>
        </div><!-- .site-title-wrap -->
	</div>    
    <?php
}
endif;

if( ! function_exists( 'the_conference_primary_mobile_nagivation' ) ) :
/**
 * Primary Navigation.
*/
function the_conference_primary_mobile_nagivation(){ 
    ?>
    <nav id="mobile-site-navigation" class="main-navigation mobile-navigation">        
        <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
            <button class="close close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal">
                <span class="toggle-bar"></span> 
                <span class="toggle-bar"></span>
            </button>
            <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'the-conference' ); ?>">
                <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'mobile-primary-menu',
                        'menu_class'     => 'nav-menu main-menu-modal',
                        'fallback_cb'    => 'the_conference_primary_menu_fallback',
                    ) );
                ?>
                <?php the_conference_custom_header_link(); ?>
            </div>
        </div>
    </nav><!-- #mobile-site-navigation -->
    <?php
}
endif;

if( ! function_exists( 'the_conference_primary_nagivation' ) ) :
/**
 * Primary Navigation.
*/
function the_conference_primary_nagivation(){ 
    ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <!-- <button type="button" class="toggle-button" >
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button> -->
            <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'nav-menu', 
                    'fallback_cb'    => 'the_conference_primary_menu_fallback',
                ) );
            ?>
        </nav><!-- #site-navigation -->
    <?php
}
endif;

if( ! function_exists( 'the_conference_primary_menu_fallback' ) ) :
/**
 * Fallback for primary menu
*/
function the_conference_primary_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<ul id="primary-menu" class="menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'the-conference' ) . '</a></li>';
        echo '</ul>';
    }
}
endif;

if( ! function_exists( 'the_conference_breadcrumb' ) ) :
/**
 * Breadcrumbs
*/
function the_conference_breadcrumb() {    
    global $post;
    
    $post_page   = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front  = get_option( 'show_on_front' ); //What to show on the front page
    $delimiter   = '<i class="fas fa-long-arrow-alt-right"></i>'; // delimiter between crumbs
    $home        = get_theme_mod( 'home_text', __( 'Home', 'the-conference' ) ); // text for the 'Home' link
    $before      = '<span class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
      
    $depth = 1;  
    if( get_theme_mod( 'ed_breadcrumb', true ) ){  
        echo '<div class="breadcrumb"><div id="crumbs" itemscope itemtype="https://schema.org/BreadcrumbList"><span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( home_url() ) . '" class="home_crumb"><span itemprop="name">' . esc_html( $home ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
            if( is_home() && ! is_front_page() ){            
                $depth = 2;
                echo $before . '<span itemprop="name">' . esc_html( single_post_title( '', false ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;          
            }elseif( is_category() ){            
                $depth = 2;
                $thisCat = get_category( get_query_var( 'cat' ), false );
                if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                    $p = get_post( $post_page );
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $post_page ) ) . '"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                    $depth ++;  
                }

                if ( $thisCat->parent != 0 ) {
                    $parent_categories = get_category_parents( $thisCat->parent, false, ',' );
                    $parent_categories = explode( ',', $parent_categories );

                    foreach ( $parent_categories as $parent_term ) {
                        $parent_obj = get_term_by( 'name', $parent_term, 'category' );
                        if( is_object( $parent_obj ) ){
                            $term_url    = get_term_link( $parent_obj->term_id );
                            $term_name   = $parent_obj->name;
                            echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                            $depth ++;
                        }
                    }
                }

                echo $before . '<span itemprop="name">' .  esc_html( single_cat_title( '', false ) ) . '</span><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;

            }elseif( is_tax( 'rara_portfolio_categories' ) ){
                //Displaying portfolio respective page template in the breadcrumbs 
                $portfolio = the_conference_get_page_id_by_template( 'templates/portfolio.php' );

                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $portfolio[0] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $portfolio[0] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth++;

                //Displaying the parent child category/terms for the respective taxonomy
                $queried_object = get_queried_object();
                $taxonomy       = 'rara_portfolio_categories';
                if( $queried_object->parent != 0 ) {
                    $parent_categories = get_term_parents_list( $queried_object->parent, $taxonomy, array( 'separator' => ',' ) );
                    $parent_categories = explode( ',', $parent_categories );
                    foreach ( $parent_categories as $parent_term ) {
                        $parent_obj = get_term_by( 'name', $parent_term,$taxonomy );
                        if( is_object( $parent_obj ) ){
                            $term_url    = get_term_link( $parent_obj->term_id );
                            $term_name   = $parent_obj->name;
                            echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                            $depth ++;
                        }
                    }
                }
                //Displaying the current viewed term object 
                echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;

            }elseif( is_tag() ){            
                $queried_object = get_queried_object();
                $depth = 2;

                echo $before . '<span itemprop="name">' . esc_html( single_tag_title( '', false ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;    
            }elseif( is_author() ){            
                $depth = 2;
                global $author;
                $userdata = get_userdata( $author );
                echo $before . '<span itemprop="name">' . esc_html( $userdata->display_name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;  
            }elseif( is_day() ){            
                $depth = 2;
                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'the-conference' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'the-conference' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth ++;
                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'the-conference' ) ), get_the_time( __( 'm', 'the-conference' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'the-conference' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth ++;
                echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'd', 'the-conference' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                 
            }elseif( is_month() ){            
                $depth = 2;
                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'the-conference' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'the-conference' ) ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth++;
                echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'F', 'the-conference' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;      
            }elseif( is_year() ){            
                $depth = 2;
                echo $before .'<span itemprop="name">'. esc_html( get_the_time( __( 'Y', 'the-conference' ) ) ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 
            }elseif( is_single() && !is_attachment() ) {
                //For Woocommerce single product            
                if( the_conference_is_woocommerce_activated() && 'product' === get_post_type() ){ 
                    if ( wc_get_page_id( 'shop' ) ) { 
                        //Displaying Shop link in woocommerce archive page
                        $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                        if ( ! $_name ) {
                            $product_post_type = get_post_type_object( 'product' );
                            $_name = $product_post_type->labels->singular_name;
                        }
                        echo ' <a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name) . '</span></a> ' . '<span class="separator">' . $delimiter . '</span>';
                    }
                
                    if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                        $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                        $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                        $ancestors = array_reverse( $ancestors );

                        foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );    
                            if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                                $depth++;
                            }
                        }
                        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $main_term->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                    }
                
                    echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                                   
                }elseif( get_post_type() == 'rara-portfolio' ){
                    $depth = 2;
                    $portfolio = the_conference_get_page_id_by_template( 'templates/portfolio.php' );

                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $portfolio[0] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $portfolio[0] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                    $depth++;

                    $cat_object = get_the_terms( get_the_ID(), 'rara_portfolio_categories' );
                    $potential_parent = 0;
                    
                    if( is_array( $cat_object ) ){ 
                        //Now try to find the deepest term of those that we know of
                        $use_term = key( $cat_object );

                        foreach( $cat_object as $key => $object ){
                            //Can't use the next($cat_object) trick since order is unknown
                            if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                                $use_term = $key;
                                $potential_parent = $object->term_id;
                            }
                        }
                        
                        $cat = $cat_object[$use_term];
                        $cats = get_term_parents_list( $cat, 'rara_portfolio_categories', array( 'separator' => ',' ) );
                        $cats = explode( ',', $cats );

                        foreach ( $cats as $cat ) {
                            $cat_obj = get_term_by( 'name', $cat, 'rara_portfolio_categories' );
                            if( is_object( $cat_obj ) ){
                                $term_url    = get_term_link( $cat_obj->term_id );
                                $term_name   = $cat_obj->name;
                                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                                $depth ++;
                            }
                        }
                    }

                    echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                }else{ 
                    //For Post                
                    $cat_object       = get_the_category();
                    $potential_parent = 0;
                    $depth            = 2;
                    
                    if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                        $p = get_post( $post_page );
                        echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';  
                        $depth++;
                    }
                    
                    if( is_array( $cat_object ) ){ //Getting category hierarchy if any
            
                        //Now try to find the deepest term of those that we know of
                        $use_term = key( $cat_object );
                        foreach( $cat_object as $key => $object ){
                            //Can't use the next($cat_object) trick since order is unknown
                            if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                                $use_term = $key;
                                $potential_parent = $object->term_id;
                            }
                        }
                        
                        $cat = $cat_object[$use_term];
                  
                        $cats = get_category_parents( $cat, false, ',' );
                        $cats = explode( ',', $cats );

                        foreach ( $cats as $cat ) {
                            $cat_obj = get_term_by( 'name', $cat, 'category' );
                            if( is_object( $cat_obj ) ){
                                $term_url    = get_term_link( $cat_obj->term_id );
                                $term_name   = $cat_obj->name;
                                echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                                $depth ++;
                            }
                        }
                    }
        
                    echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                                 
                }        
            }elseif( is_page() && !$post->post_parent ){            
                $depth = 2;
                echo $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 
            }elseif( is_page() && $post->post_parent ){            
                $depth       = 2;
                $parent_id   = $post->post_parent;
                $breadcrumbs = array();
                while( $parent_id ){
                    $current_page  = get_post( $parent_id );
                    $breadcrumbs[] = $current_page->ID;
                    $parent_id     = $current_page->post_parent;
                }
                $breadcrumbs = array_reverse( $breadcrumbs );
                for ( $i = 0; $i < count( $breadcrumbs) ; $i++ ){
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $breadcrumbs[$i] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $breadcrumbs[$i] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></span>';
                    if ( $i != count( $breadcrumbs ) - 1 ) echo ' <span class="separator"><i class="fas fa-long-arrow-alt-right"></i></span> ';
                        $depth++;
                    $depth++;
                }
                echo ' <span class="separator"><i class="fas fa-long-arrow-alt-right"></i></span> ' . $before .'<span itemprop="name">'. esc_html( get_the_title() ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" /></span>'. $after;      
            }elseif( is_search() ){            
                $depth = 2;
                echo $before .'<span itemprop="name">'. esc_html__( 'Search Results for "', 'the-conference' ) . esc_html( get_search_query() ) . esc_html__( '"', 'the-conference' ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;      
            }elseif( the_conference_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ 
                //For Woocommerce archive page        
                $depth = 2;
                if ( wc_get_page_id( 'shop' ) ) { 
                    //Displaying Shop link in woocommerce archive page
                    $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                    if ( ! $_name ) {
                        $product_post_type = get_post_type_object( 'product' );
                        $_name = $product_post_type->labels->singular_name;
                    }
                    echo ' <a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name) . '</span></a> ' . '<span class="separator">' . $delimiter . '</span>';
                }
                $current_term = $GLOBALS['wp_query']->get_queried_object();
                if( is_product_category() ){
                    $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                            $depth ++;
                        }
                    }
                }           
                echo $before . '<span itemprop="name">' . esc_html( $current_term->name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />' . $after;           
            }elseif( the_conference_is_woocommerce_activated() && is_shop() ){ //Shop Archive page
                $depth = 2;
                if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                    return;
                }
                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                $shop_url = wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
        
                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }
                echo $before . '<span itemprop="name">' . esc_html( $_name ) .'</span><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;                    
            }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {            
                $depth = 2;
                $post_type = get_post_type_object(get_post_type());
                if( get_query_var('paged') ){
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />';
                    echo ' <span class="separator">' . $delimiter . '</span></span> ' . $before . sprintf( __('Page %s', 'the-conference'), get_query_var('paged') ) . $after;
                }elseif( is_archive() ){
                    echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                }else{
                    echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                }              
            }elseif( is_attachment() ){            
                $depth  = 2;
                $parent = get_post( $post->post_parent );
                $cat    = get_the_category( $parent->ID );
                if( $cat ){
                    $cat = $cat[0];
                    echo get_category_parents( $cat, TRUE, ' <span class="separator">' . $delimiter . '</span> ');
                    echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $parent ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $parent->post_title ) . '<span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . ' <span class="separator">' . $delimiter . '</span></span>';
                }
                echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;   
            }elseif ( is_404() ){
                echo $before . esc_html__( '404 Error - Page not Found', 'the-conference' ) . $after;
            }
            if( get_query_var('paged') ) echo __( ' (Page', 'the-conference' ) . ' ' . get_query_var('paged') . __( ')', 'the-conference' );        
            echo '</div></div>';
    }
}
endif;

if( ! function_exists( 'the_conference_theme_comment' ) ) :
/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function the_conference_theme_comment( $comment, $args, $depth ){
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	
    <?php if ( 'div' != $args['style'] ) : ?>
    <article id="div-comment-<?php comment_ID() ?>" class="comment-body" itemscope itemtype="https://schema.org/UserComments">
	<?php endif; ?>
    	
        <footer class="comment-meta">
            <div class="comment-author vcard">
        	   <?php 
                    if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] );
                    /* translators: %s: comment author link */
                    printf( __( '<b class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person">%s<span class="says">says:</span></b>', 'the-conference' ), get_comment_author_link() ); 
                ?>
            </div><!-- .comment-author vcard -->

            <div class="comment-metadata commentmetadata">
                <?php esc_html_e( 'Posted on', 'the-conference' );?>
                <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
                    <time itemprop="commentTime" datetime="<?php echo esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ); ?>">
                    <?php 
                    /* translators: 1: comment date, 2: comment time */
                    printf( esc_html__( '%1$s at %2$s', 'the-conference' ), get_comment_date(),  get_comment_time() ); 
                    ?>
                    </time>
                </a>
            </div>

            <?php if ( $comment->comment_approved == '0' ) : ?>
                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'the-conference' ); ?></p>
                <br />
            <?php endif; ?>
        </footer>
        
        <div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>        
        
         <div class="reply">
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>
        
	<?php if ( 'div' != $args['style'] ) : ?>
    </article><!-- .comment-body -->
	<?php endif; ?>
    
<?php
}
endif;

if( ! function_exists( 'the_conference_sidebar_layout' ) ) :
/**
 * Return sidebar layouts for pages/posts
*/
function the_conference_sidebar_layout( $class = false ){
    global $post;
    $return      = false;

    $page_layout    = get_theme_mod( 'page_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Pages
    $post_layout    = get_theme_mod( 'post_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Posts
    $general_layout = get_theme_mod( 'layout_style', 'right-sidebar' ); //Default Layout

    $show_on_front  = get_option( 'show_on_front' );
    $blogpage_id    = get_option( 'page_for_posts' );
    $frontpage_id   = get_option( 'page_on_front' );
    $home_sections  = the_conference_get_home_sections();
    
    if( is_front_page() && ! is_home() ){
        if( $home_sections ){
            $return = $class ? '' : false;
        }else{
            $frontpage_layout = get_post_meta( $frontpage_id, '_the_conference_sidebar_layout', true );
            $frontpage_layout = ! empty( $frontpage_layout ) ? $frontpage_layout : 'default-sidebar';

            if( $frontpage_layout == 'no-sidebar' ){
                $return = $class ? 'full-width' : false;
            }elseif( $frontpage_layout == 'centered' ){
                $return = $class ? 'full-width-centered' : false;
            }elseif( ( $frontpage_layout == 'default-sidebar' && $general_layout == 'right-sidebar' ) || ( $frontpage_layout == 'right-sidebar' ) ){
                $return = $class ? 'rightsidebar' : 'sidebar';
            }elseif( ( $frontpage_layout == 'default-sidebar' && $general_layout == 'left-sidebar' ) || ( $frontpage_layout == 'left-sidebar' ) ){
                $return = $class ? 'leftsidebar' : 'sidebar';
            }elseif( $frontpage_layout == 'default-sidebar' && $general_layout == 'centered' ){
                $return = $class ? 'full-width-centered' :  false;
            }elseif( $frontpage_layout == 'default-sidebar' && $general_layout == 'no-sidebar' ){
                $return = $class ? 'full-width' : false;
            }
        }
    }elseif( is_home() ){
        if( 'page' == $show_on_front && $blogpage_id > 0 ){
            $blogpage_layout = get_post_meta( $blogpage_id, '_the_conference_sidebar_layout', true );
            $blogpage_layout = ! empty( $blogpage_layout ) ? $blogpage_layout : 'default-sidebar';

            if( $blogpage_layout == 'no-sidebar' ){
                $return = $class ? 'full-width' : false;
            }elseif( $blogpage_layout == 'centered' ){
                $return = $class ? 'full-width-centered' : false;
            }elseif( ( $blogpage_layout == 'default-sidebar' && $general_layout == 'right-sidebar' ) || ( $blogpage_layout == 'right-sidebar' ) ){
                $return = $class ? 'rightsidebar' : 'sidebar';
            }elseif( ( $blogpage_layout == 'default-sidebar' && $general_layout == 'left-sidebar' ) || ( $blogpage_layout == 'left-sidebar' ) ){
                $return = $class ? 'leftsidebar' : 'sidebar';
            }elseif( $blogpage_layout == 'default-sidebar' && $general_layout == 'centered' ){
                $return = $class ? 'full-width-centered' :  false;
            }elseif( $blogpage_layout == 'default-sidebar' && $general_layout == 'no-sidebar' ){
                $return = $class ? 'full-width' : false;
            }

        }elseif( is_active_sidebar( 'sidebar' ) ){            
            if( $general_layout == 'right-sidebar' ){
                $return = $class ? 'rightsidebar' : 'sidebar';
            }elseif( $general_layout == 'left-sidebar' ){
                $return = $class ? 'leftsidebar' : 'sidebar';
            }else{
                $return = $class ? 'full-width' : false;
            }
        }else{
            $return = $class ? 'full-width' : false;
        }        
    }elseif( is_singular( array( 'page', 'post' ) ) ){   
        $sidebar_layout = get_post_meta( $post->ID, '_the_conference_sidebar_layout', true );
        $sidebar_layout = ! empty( $sidebar_layout ) ? $sidebar_layout : 'default-sidebar';
        
        if( is_page() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' ){
                    $return = $class ? 'full-width-centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }elseif( $sidebar_layout == 'default-sidebar' && $page_layout == 'centered' ){
                    $return = $class ? 'full-width-centered' :  false;
                }elseif( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ){
                    $return = $class ? 'full-width' : false;
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }elseif( is_single() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' ){
                    $return = $class ? 'full-width-centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }elseif( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ){
                    $return = $class ? 'full-width-centered' : false;
                }elseif( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ){
                    $return = $class ? 'full-width' : false;
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }
    }elseif( is_tax( 'rara_portfolio_categories' ) ){
        if( is_active_sidebar( 'sidebar' ) ){
            if( 'right-sidebar' == $general_layout ){
                $return = $class ? 'rightsidebar' : 'sidebar'; 
            }elseif( 'left-sidebar' == $general_layout ){
                $return = $class ? 'leftsidebar' : 'sidebar'; 
            }else{
                $return = $class ? 'full-width' : false; //Fullwidth
            }
        }else{
            $return = $class ? 'full-width' : false; //Fullwidth
        }                                                       
    }elseif( is_singular( 'rara-portfolio' ) ){
        $return = $class ? 'full-width' : false;
    }elseif( the_conference_is_woocommerce_activated() && is_post_type_archive( 'product' ) ){
        if( is_active_sidebar( 'shop-sidebar' ) ){            
            $return = $class ? 'rightsidebar' : 'sidebar';             
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }elseif( is_404() ){
        $return = $class ? 'full-width-centered' : false;
    }else{
        if( is_active_sidebar( 'sidebar' ) ){
            if( 'right-sidebar' == $general_layout ){
                $return = $class ? 'rightsidebar' : 'sidebar'; 
            }elseif( 'left-sidebar' == $general_layout ){
                $return = $class ? 'leftsidebar' : 'sidebar'; 
            }else{
                $return = $class ? 'full-width' : false; //Fullwidth
            }
        }else{
            $return = $class ? 'full-width' : false; //Fullwidth
        }         
    }

    return $return; 
}
endif;

if( ! function_exists( 'the_conference_get_home_sections' ) ) :
/**
 * Returns Home Sections 
*/
function the_conference_get_home_sections(){
    $sections = array( 
        'about'             => array( 'sidebar' => 'about' ), 
        'stat-counter'      => array( 'sidebar' => 'stat-counter' ), 
        'recent-conference' => array( 'sidebar' => 'recent-conference' ), 
        'speakers'          => array( 'sidebar' => 'speakers' ), 
        'testimonial'       => array( 'sidebar' => 'testimonial' ), 
        'cta'               => array( 'sidebar' => 'cta' ), 
        'blog'              => array( 'section' => 'blog' ), 
        'contact'           => array( 'sidebar' => 'contact' ), 
        'gmap'              => array( 'sidebar' => 'gmap' ), 
    );
    
    $enabled_section = array();
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', true ) ) array_push( $enabled_section, $v['section'] );
        }
    }  
    
    return apply_filters( 'the_conference_home_sections', $enabled_section );
}
endif;

if( ! function_exists( 'the_conference_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function the_conference_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'the_conference_fallback_image' ) ) :
/**
 * Prints Fallback Images
*/
function the_conference_fallback_image( $image_size, $id = 0 ){
    $placeholder = get_template_directory_uri() . '/images/fallback/' . $image_size . '.jpg';
    
    echo '<img src="' . esc_url( $placeholder ) . '" alt="'. the_title_attribute( 'echo=0' ) .'" itemprop="image"/>';
}
endif;

/**
 * Is BlossomThemes Email Newsletters active or not
*/
function the_conference_is_btnw_activated(){
    return class_exists( 'Blossomthemes_Email_Newsletter' ) ? true : false;        
}

/**
 * Query WooCommerce activation
 */
function the_conference_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

/**
 * Check if Contact Form 7 Plugin is installed
*/
function the_conference_is_cf7_activated(){
    return class_exists( 'WPCF7' ) ? true : false;
}

/**
 * Query Rara theme companion activation
 */
function the_conference_is_rara_theme_companion_activated() {
    return class_exists( 'Raratheme_Companion_Public' ) ? true : false;
}

/**
 * Query Jetpack activation
*/
function the_conference_is_jetpack_activated( $gallery = false ){
	if( $gallery ){
        return ( class_exists( 'jetpack' ) && Jetpack::is_module_active( 'tiled-gallery' ) ) ? true : false;
	}else{
        return class_exists( 'jetpack' ) ? true : false;
    }           
}

if( ! function_exists( 'the_conference_get_svg' ) ) :
    /**
     * Return SVG markup.
     *
     * @param array $args {
     *     Parameters needed to display an SVG.
     *
     *     @type string $icon  Required SVG icon filename.
     *     @type string $title Optional SVG title.
     *     @type string $desc  Optional SVG description.
     * }
     * @return string SVG markup.
     */
    function the_conference_get_svg( $args = array() ) {
        // Make sure $args are an array.
        if ( empty( $args ) ) {
            return __( 'Please define default parameters in the form of an array.', 'the-conference' );
        }

        // Define an icon.
        if ( false === array_key_exists( 'icon', $args ) ) {
            return __( 'Please define an SVG icon filename.', 'the-conference' );
        }

        // Set defaults.
        $defaults = array(
            'icon'        => '',
            'title'       => '',
            'desc'        => '',
            'fallback'    => false,
        );

        // Parse args.
        $args = wp_parse_args( $args, $defaults );

        // Set aria hidden.
        $aria_hidden = ' aria-hidden="true"';

        // Set ARIA.
        $aria_labelledby = '';

        /*
         * The Conference doesn't use the SVG title or description attributes; non-decorative icons are described with .screen-reader-text.
         *
         * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
         *
         * Example 1 with title: <?php echo the_conference_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
         *
         * Example 2 with title and description: <?php echo the_conference_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
         *
         * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
         */
        if ( $args['title'] ) {
            $aria_hidden     = '';
            $unique_id       = uniqid();
            $aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

            if ( $args['desc'] ) {
                $aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
            }
        }

        // Begin SVG markup.
        $svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

        // Display the title.
        if ( $args['title'] ) {
            $svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

            // Display the desc only if the title is already set.
            if ( $args['desc'] ) {
                $svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
            }
        }

        /*
         * Display the icon.
         *
         * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
         *
         * See https://core.trac.wordpress.org/ticket/38387.
         */
        $svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

        // Add some markup to use as a fallback for browsers that do not support SVGs.
        if ( $args['fallback'] ) {
            $svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
        }

        $svg .= '</svg>';

        return $svg;
    }
endif;

if( ! function_exists( 'the_conference_header_banner' ) ) :
/**
 * Prints header banner
*/
function the_conference_header_banner(){ 
    global $post;

    if( is_home() || is_archive() || is_search() ){
        if( has_header_image() ){
            $image_url = get_header_image();
        }else{
            $image_url = get_template_directory_uri() . '/images/fallback/the-conference-banner-slider.jpg';
        }
    }elseif( is_object( $post ) && in_array( get_post_type(), array( 'post', 'page' ) ) ){
        if( has_post_thumbnail( $post->ID ) ){
            $image_url = get_the_post_thumbnail_url( $post->ID, 'the-conference-banner-slider' );
        }elseif( has_header_image() ){
            $image_url = get_header_image();
        }else{
            $image_url = get_template_directory_uri() . '/images/fallback/the-conference-banner-slider.jpg';
        }
    }elseif( has_header_image() ){
        $image_url = get_header_image();
    }else{
        $image_url = get_template_directory_uri() . '/images/fallback/the-conference-banner-slider.jpg';
    }

    $style = '';

    if( $image_url ){
        $style = 'style="background: url('. esc_url( $image_url ) .') no-repeat;"';
    }
    ?>
    
    <header class="page-header"<?php echo $style; ?>>
        <div class="container">
            <?php 

                if( is_front_page() && is_home() ){
                    $blog_title = get_theme_mod( 'blog_section_title', __( 'Recent Posts', 'the-conference' ) ); 
                    echo '<h2 class="page-title">'. esc_html( $blog_title ) .'</h2>';
                }

                if ( is_home() && ! is_front_page() ){ 
                    echo '<h2 class="page-title">' . single_post_title( '', false ) . '</h2>';
                }

                if( is_archive() ){
                    if( is_author() ){
                        $author_name = get_the_author_meta( 'display_name' );
                        $author_bio  = get_the_author_meta( 'description' );
                        ?>
                        <div class="about-author">
                            <figure class="author-img">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
                            </figure>
                            <div class="author-content-wrap">
                                <?php 
                                    if( $author_name ){ 
                                        echo '<h3 class="author-name"><span class="title-wrap"><b>'. esc_html__( 'All Posts By :', 'the-conference' ) .'</b> '. esc_html( $author_name ) .'</span>
                                    </h3>';
                                    }

                                    if( $author_bio ){
                                        echo ' <div class="author-info">'. wp_kses_post( get_the_author_meta( 'description' ) ) .'</div>';
                                    }
                                ?>
                            </div>
                        </div> <!-- .about-author -->
                        <?php 
                    }else{
                        the_archive_title();
                        the_archive_description( '<div class="archive-description">', '</div>' ); 
                    }
                }

                if( is_search() ){ 
                    global $wp_query;
                    echo '<h1 class="page-title">' . esc_html__( 'SEARCH RESULTS FOR:', 'the-conference' ) . '</h1>';
                    get_search_form();
                }
                
                if( is_single() ){
                    $hide_cat_single = get_theme_mod( 'ed_category', false );
                    if( ! $hide_cat_single ) the_conference_category();
                }

                if( is_singular() ){
                    the_title( '<h1 class="page-title">', '</h1>' );
                }

                if( is_single() && 'post' === get_post_type() ){
                    $hide_post_date     = get_theme_mod( 'ed_post_date', false );
                    $hide_post_author   = get_theme_mod( 'ed_post_author', false );
                    $hide_comment_count = get_theme_mod( 'ed_post_comment_count', false );
                    $author_id          = $post->post_author;

                    echo '<div class="entry-meta">';
                        if( ! $hide_post_author ) the_conference_posted_by( $author_id );
                        if( ! $hide_post_date ) the_conference_posted_on();
                        if( ! $hide_comment_count ) the_conference_comment_count();
                    echo '</div>';
                }

                if( is_404() ){
                    echo '<h1 class="page-title">'. esc_html__( 'Error 404', 'the-conference' ) .'</h1>';
                }

                if( ! ( ( is_home() && is_front_page() ) || is_author() || is_search() || ( is_single() && 'post' === get_post_type() ) ) ){
                    the_conference_breadcrumb();
                }
            ?>
        </div>
    </header>
<?php
}
endif;

if( ! function_exists( 'the_conference_get_posts_list' ) ) :
/**
 * Returns Latest, Related Posts
*/
function the_conference_get_posts_list( $status ){
    global $post;

    $args = array(        
        'posts_status'   => 'publish',        
        'posts_per_page' => 3
    );
    
    switch( $status ){
        case 'latest':        
        $title                        = __( 'Latest Articles', 'the-conference' );
        $args['ignore_sticky_posts']  = true;
        $args['post_type']            = 'post';
        break;
        
        case 'related':
        $args['post__not_in']         = array( $post->ID );
        $args['orderby']              = 'rand';
        $args['ignore_sticky_posts']  = true;
        $args['post_type']            = 'post';
        $title                        = get_theme_mod( 'related_post_title', __( 'Recommended Articles', 'the-conference' ) );

        $cats = get_the_category( $post->ID );        
        if( $cats ){
            $c = array();
            foreach( $cats as $cat ){
                $c[] = $cat->term_id; 
            }
            $args['category__in'] = $c;
        }
        break;
    }
    
    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() ){ ?>    
        <div class="related-post">
            <?php 
            if( $title ) echo '<h3 class="post-title"><span class="title-wrap">' . esc_html( $title ) . '</span></h3>'; 
                echo '<div class="article-wrap">';
                while( $qry->have_posts() ){ $qry->the_post(); ?>
                    <article class="post">
                        <figure class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                    if( has_post_thumbnail() ){
                                        the_post_thumbnail( 'the-conference-related', array( 'itemprop' => 'image' ) );
                                    }else{ 
                                        the_conference_fallback_image( 'the-conference-related' );
                                    }
                                ?>
                            </a>
                        </figure>
                        <header class="entry-header">
                            <div class="entry-meta">
                                <?php the_conference_posted_on(); ?>
                            </div> 
                            <?php  the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>                       
                        </header>
                    </article>
                <?php }
                echo '</div><!-- .article-wrap -->'
                ?>           
            </div>
        <?php
        wp_reset_postdata();
    }
}
endif;

if( ! function_exists( 'the_conference_number_of_widgets_in_sidebar' ) ) :
/**
 * Count the number of widgets in a sidebar
 * Works for up to ten widgets
 * Usage <?php the_conference_number_of_widgets_in_sidebar( 'promo' ); ?> where promo is the name of the sidebar
 */
function the_conference_number_of_widgets_in_sidebar( $sidebar_name ) {
    global $sidebars_widgets;

    $count = 0;
    $class = '';

    if( isset( $sidebars_widgets[$sidebar_name] ) && '' != $sidebars_widgets[$sidebar_name] ){
        $count = count ($sidebars_widgets[$sidebar_name]);
    }

    if( $count > 0 ){
        $class = ' active-widget-' . $count;
    }

    return $class;
}
endif;

if( ! function_exists( 'the_conference_custom_header_link' ) ) :
    /**
     * Additional Link in menu
     */
    function the_conference_custom_header_link(){
        $label           = get_theme_mod( 'custom_link_label', __( 'BUY TICKET', 'the-conference' ) );
        $link            = get_theme_mod( 'custom_link', '#' );
        $ed_new_tab      = get_theme_mod( 'ed_custom_link_tab', false );
        $target          = $ed_new_tab ? ' target="_blank"' : '';

        if( $link && $label){
            echo '<div class="nav-btn"><a href="' . esc_url( $link ) . '" class="btn custom-link"' . $target . '>' . esc_html( $label ) . '</a></div>';
        }
    }
endif;

if( ! function_exists( 'the_conference_fonts_url' ) ) :
    /**
     * Register custom fonts.
     */
    function the_conference_fonts_url() {
        $fonts_url = '';

        /* Translators: If there are characters in your language that are not
        * supported by respective fonts, translate this to 'off'. Do not translate
        * into your own language.
        */

        $nunito_font       = _x( 'on', 'Nunito Sans: on or off', 'the-conference' );

        if ( 'off' !== $nunito_font ) {
            $font_families = array();

            $font_families[] = 'Nunito Sans:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i';

            $query_args = array(
                'family' => implode( '|', $font_families ),
                'subset' => 'latin,latin-ext',
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return esc_url( $fonts_url );
    }
endif;

if( ! function_exists( 'the_conference_ed_author_link' ) ) :
    /**
     * Author link in footer
    */
    function the_conference_ed_author_link(){
        echo '<span class="author-link">' . esc_html__( 'The Conference | Developed by ', 'the-conference' ) . '<a href="' . esc_url( 'https://rarathemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Rara Theme', 'the-conference' ) . '</a></span>';
    }
endif;

if( ! function_exists( 'the_conference_ed_wp_link' ) ) :
    /**
     * WordPress link in footer
    */
    function the_conference_ed_wp_link(){
        /* translators: 1: span tag, 2: WordPress link */
        printf( esc_html__( '%1$s Powered by %2$s%3$s', 'the-conference' ), '<span class="wp-link">', '<a href="'. esc_url( __( 'https://wordpress.org/', 'the-conference' ) ) .'" target="_blank">WordPress</a>.', '</span>' );
    }
endif;

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;
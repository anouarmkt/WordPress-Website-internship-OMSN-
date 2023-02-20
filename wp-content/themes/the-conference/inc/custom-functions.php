<?php
/**
 * The Conference Custom functions and definitions
 *
 * @package The Conference
 */

if ( ! function_exists( 'the_conference_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function the_conference_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Conference, use a find and replace
	 * to change 'the-conference' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'the-conference', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary', 'the-conference' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-list',
		'gallery',
		'caption',
	) );
    
    // Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'the_conference_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
    
	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 
        'custom-logo', 
        array( 
            'width'       => 185,
            'height'      => 80, 
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ) 
        ) 
    );
    
    /**
     * Add support for custom header.
    */
    add_theme_support( 'custom-header', apply_filters( 'the_conference_custom_header_args', array(
		'default-image' => get_template_directory_uri() . '/images/banner-img.jpg',
        'video'         => true,
		'width'         => 1920,
		'height'        => 1008, 
		'header-text'   => false
	) ) );

    // Register default headers.
    register_default_headers( array(
        'default-banner' => array(
            'url'           => '%s/images/banner-img.jpg',
            'thumbnail_url' => '%s/images/banner-img.jpg',
            'description'   => esc_html_x( 'Default Banner', 'header image description', 'the-conference' ),
        ),
    ) );
 
    /**
     * Add Custom Images sizes.
    */    
    add_image_size( 'the-conference-banner-slider', 1920, 1008, true );
    add_image_size( 'the-conference-featured-page', 470, 470, true );
    add_image_size( 'the-conference-icon-text-image', 585, 550, true );
    add_image_size( 'the-conference-speaker', 384, 467, true );
    add_image_size( 'the-conference-blog', 768, 519, true );
    add_image_size( 'the-conference-blog-fullwidth', 1320, 519, true );
    add_image_size( 'the-conference-related', 110, 83, true );
    add_image_size( 'the-conference-schema', 600, 60, true );    
    
    /** Starter Content */
    $starter_content = array(
        // Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array( 
            'home', 
            'blog',
        ),
		
        // Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),
        
        // Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'primary' => array(
				'name' => __( 'Primary', 'the-conference' ),
				'items' => array(
					'page_home',
					'page_blog',
				)
			)
		),
    );
    
    $starter_content = apply_filters( 'the_conference_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
    
    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );

    // Add excerpt support for pages
    add_post_type_support( 'page', 'excerpt' );

    remove_theme_support( 'widgets-block-editor' );
}
endif;
add_action( 'after_setup_theme', 'the_conference_setup' );

if( ! function_exists( 'the_conference_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function the_conference_content_width() {
	/** 
     * content width.
    */
    $GLOBALS['content_width'] = apply_filters( 'the_conference_content_width', 640 );
}
endif;
add_action( 'after_setup_theme', 'the_conference_content_width', 0 );

if( ! function_exists( 'the_conference_template_redirect_content_width' ) ) :
/**
* Adjust content_width value according to template.
*
* @return void
*/
function the_conference_template_redirect_content_width(){
    if( is_active_sidebar( 'sidebar') ){	   
        /** 
         * content width when sidebar is active.
        */
        $GLOBALS['content_width'] = 640;       
	}else{
        /** 
         * content width in singular page.
        */
        if( is_singular() ){
            if( the_conference_sidebar_layout( true ) === 'full-width-centered' ){
                $GLOBALS['content_width'] = 640;
            }else{
                $GLOBALS['content_width'] = 1320;                
            }                
        }else{
            $GLOBALS['content_width'] = 1320;
        }
	}
}
endif;
add_action( 'template_redirect', 'the_conference_template_redirect_content_width' );

if( ! function_exists( 'the_conference_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function the_conference_scripts() {
	// Use minified libraries if SCRIPT_DEBUG is false
    $build         = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix        = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    $rtc_activated = the_conference_is_rara_theme_companion_activated();

    if( the_conference_is_woocommerce_activated() )
    wp_enqueue_style( 'the-conference-woocommerce', get_template_directory_uri(). '/css' . $build . '/woocommerce' . $suffix . '.css', array(), THE_CONFERENCE_THEME_VERSION );
    
    wp_enqueue_style( 'animate', get_template_directory_uri(). '/css' . $build . '/animate' . $suffix . '.css', array(), '3.5.2' );
    wp_enqueue_style( 'the-conference-google-fonts', the_conference_fonts_url(), array(), null );
    wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '6.1.1', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery' ), '6.1.1', true );

    wp_enqueue_script( 'jquery-countdown', get_template_directory_uri() . '/js' . $build . '/jquery.countdown' . $suffix . '.js', array( 'jquery' ), '2.2.0', true );

    if( $rtc_activated && is_active_widget( false, false, 'rrtc_description_widget' ) ){
        wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri(). '/css' . $build . '/perfect-scrollbar' . $suffix . '.css', array(), '1.3.0' );
        wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js' . $build . '/perfect-scrollbar' . $suffix . '.js', array( 'jquery' ), '1.3.0', true ); 
    }

    wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/js' . $build . '/waypoints' . $suffix . '.js', array( 'jquery' ), '2.0.3', true );
    wp_enqueue_script( 'wow', get_template_directory_uri() . '/js' . $build . '/wow' . $suffix . '.js', array( 'jquery' ), '2.0.3', true );
    wp_enqueue_script( 'the-conference-modal-accessibility', get_template_directory_uri() . '/js' . $build . '/modal-accessibility' . $suffix . '.js', array( 'jquery' ), THE_CONFERENCE_THEME_VERSION, true );
	wp_enqueue_script( 'the-conference', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-core' ), THE_CONFERENCE_THEME_VERSION, true );
    
    $banner_control        = get_theme_mod( 'ed_banner_section', 'static_banner' );
    $ed_banner_event_timer = get_theme_mod( 'ed_banner_event_timer', true );
    $banner_event_timer    = new DateTime( get_theme_mod( 'banner_event_timer', '2020-08-20' ) );
    $today                 = new DateTime( date('Y-m-d') );
    $banner_timer          = '';

    if( $ed_banner_event_timer && ( is_front_page() && ! is_home() ) && 'static_banner' == $banner_control ){
        if( $banner_event_timer > $today ){
            $banner_timer = get_theme_mod( 'banner_event_timer', '2020-08-20' );        
        }
    }

    $array = array( 
        'rtl'                => is_rtl(),
        'singular'           => is_singular(),
        'banner_event_timer' => $banner_timer,
    );
    
    wp_localize_script( 'the-conference', 'the_conference_data', $array );
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

    wp_enqueue_style( 'the-conference', get_stylesheet_uri(), array(), THE_CONFERENCE_THEME_VERSION );
}
endif;
add_action( 'wp_enqueue_scripts', 'the_conference_scripts' );

if( ! function_exists( 'the_conference_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function the_conference_admin_scripts(){
    wp_enqueue_style( 'the-conference-admin', get_template_directory_uri() . '/inc/css/admin.css', '', THE_CONFERENCE_THEME_VERSION );
}
endif; 
add_action( 'admin_enqueue_scripts', 'the_conference_admin_scripts' );

if( ! function_exists( 'the_conference_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function the_conference_body_classes( $classes ) {
    $banner_control      = get_theme_mod( 'ed_banner_section', 'static_banner' );
    $header_image        = get_header_image_tag(); // get custom header image tag
    $blog_layout         = get_theme_mod( 'blog_page_layout', 'classic-view' );

    // Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    if( is_front_page() && ! is_home() && 'no_banner' != $banner_control && ( has_header_video() ||  ! empty( $header_image ) ) ){
        $classes[] = 'homepage hasbanner';
    }
    
    if( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
    $classes[] = the_conference_sidebar_layout( true );

    if( is_home() ){
        switch( $blog_layout ){
            case 'classic-view':
            $classes[] = 'classic-view';
            break;
            case 'list-view':
            $classes[] = 'list-view';
            break;
        }
    }

    if( is_archive() || is_search() ){
        $classes[] = 'list-view';
    }
    
	return $classes;
}
endif;
add_filter( 'body_class', 'the_conference_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function the_conference_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'the_conference_pingback_header' );

if( ! function_exists( 'the_conference_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function the_conference_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required = ( $req ? " required" : '' );
    $author   = ( $req ? __( 'Name*', 'the-conference' ) : __( 'Name', 'the-conference' ) );
    $email    = ( $req ? __( 'Email*', 'the-conference' ) : __( 'Email', 'the-conference' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'the-conference' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $author ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'the-conference' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $email ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'the-conference' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'the-conference' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'the_conference_change_comment_form_default_fields' );

if( ! function_exists( 'the_conference_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function the_conference_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'the-conference' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'the-conference' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'the_conference_change_comment_form_defaults' );

if ( ! function_exists( 'the_conference_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function the_conference_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}

endif;
add_filter( 'excerpt_more', 'the_conference_excerpt_more' );

if ( ! function_exists( 'the_conference_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function the_conference_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod( 'excerpt_length', 55 );
    return is_admin() ? $length : absint( $excerpt_length );    
}
endif;
add_filter( 'excerpt_length', 'the_conference_excerpt_length', 999 );

if( ! function_exists( 'the_conference_get_the_archive_title' ) ) :
/**
 * Filter Archive Title
*/
function the_conference_get_the_archive_title( $title ){
    $ed_prefix = get_theme_mod( 'ed_prefix_archive', false );
    
    if( is_category() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>';
        }else{
            /* translators: Category archive title. 1: Category name */
            $title = sprintf( __( '%1$sCategory%2$s %3$s', 'the-conference' ), '<span class="sub-title">', '</span>', '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>' );
        }
    }elseif ( is_tag() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>';    
        }else{
            /* translators: Tag archive title. 1: Tag name */
            $title = sprintf( __( '%1$sTag%2$s %3$s', 'the-conference' ), '<span class="sub-title">', '</span>', '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>' );
        }
    }elseif ( is_year() ) {
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'the-conference' ) ) . '</h1>';
        }else{
            /* translators: Yearly archive title. 1: Year */
            $title = sprintf( __( '%1$sYear%2$s %3$s', 'the-conference' ), '<span class="sub-title">', '</span>', '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'the-conference' ) ) . '</h1>' );
        }
    }elseif ( is_month() ) {
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'the-conference' ) ) . '</h1>';
        }else{
            /* translators: Monthly archive title. 1: Month name and year */
            $title = sprintf( __( '%1$sMonth%2$s %3$s', 'the-conference' ), '<span class="sub-title">', '</span>', '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'the-conference' ) ) . '</h1>' );
        }
    }elseif ( is_day() ) {
       if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'the-conference' ) ) . '</h1>';
        }else{
            /* translators: Daily archive title. 1: Date */
            $title = sprintf( __( '%1$sDay%2$s %3$s', 'the-conference' ), '<span class="sub-title">', '</span>', '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'the-conference' ) ) . '</h1>' );
        }
    }elseif ( is_post_type_archive() ) {
        if( is_post_type_archive( 'product' ) ){
            $title = '<h1 class="page-title">' . get_the_title( get_option( 'woocommerce_shop_page_id' ) ) . '</h1>';
        }else{
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' . post_type_archive_title( '', false ) . '</h1>';
            }else{
                /* translators: Post type archive title. 1: Post type name */
                $title = sprintf( __( '%1$sArchives%2$s %3$s', 'the-conference' ), '<span class="sub-title">', '</span>', '<h1 class="page-title">' . post_type_archive_title( '', false ) . '</h1>' );
            }
        }
    }elseif ( is_tax() ) {
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>';
        }else{                                                            
            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = sprintf( __( '%1$s: %2$s', 'the-conference' ), '<span class="sub-title">' . $tax->labels->singular_name . '</span>', '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>' );
        }
    }
        
    return $title;
    
}
endif;
add_filter( 'get_the_archive_title', 'the_conference_get_the_archive_title' );


if( ! function_exists( 'the_conference_get_comment_author_link' ) ) :
/**
 * Filter to modify comment author link
 * @link https://developer.wordpress.org/reference/functions/get_comment_author_link/
 */
function the_conference_get_comment_author_link( $return, $author, $comment_ID ){
    $comment = get_comment( $comment_ID );
    $url     = get_comment_author_url( $comment );
    $author  = get_comment_author( $comment );
 
    if ( empty( $url ) || 'http://' == $url )
        $return = '<span itemprop="name">'. esc_html( $author ) .'</span>';
    else
        $return = '<span itemprop="name"><a href=' . esc_url( $url ) . ' rel="external nofollow noopener" class="url" itemprop="url">' . esc_html( $author ) . '</a></span>';

    return $return;
}
endif;
add_filter( 'get_comment_author_link', 'the_conference_get_comment_author_link', 10, 3 );

if ( !function_exists( 'the_conference_video_controls' ) ) :
/**
 * Customize video play/pause button in the custom header.
 *
 * @param array $settings Video settings.
 */
function the_conference_video_controls( $settings ) {
    $settings['l10n']['play'] = '<span class="screen-reader-text">' . __( 'Play background video', 'the-conference' ) . '</span>' . the_conference_get_svg( array( 'icon' => 'play' ) );
    $settings['l10n']['pause'] = '<span class="screen-reader-text">' . __( 'Pause background video', 'the-conference' ) . '</span>' . the_conference_get_svg( array( 'icon' => 'pause' ) );
    return $settings;
}
endif;
add_filter( 'header_video_settings', 'the_conference_video_controls' );

if( ! function_exists( 'the_conference_include_svg_icons' ) ) :
/**
 * Add SVG definitions to the footer.
 */
function the_conference_include_svg_icons() {
    // Define SVG sprite file.
    $svg_icons = get_parent_theme_file_path( '/images/svg-icons.svg' );

    // If it exists, include it.
    if ( file_exists( $svg_icons ) ) {
        require_once( $svg_icons );
    }
}
endif;
add_action( 'wp_footer', 'the_conference_include_svg_icons', 9999 );

if( ! function_exists( 'the_conference_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function the_conference_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'the_conference_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'the-conference' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'the-conference' ), esc_html( $name ) ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=the-conference-getting-started' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to the getting started.', 'the-conference' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?the_conference_admin_notice=1"><?php esc_html_e( 'Dismiss', 'the-conference' ); ?></a></strong></p>
                </div>
            </div>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'the_conference_admin_notice' );

if( ! function_exists( 'the_conference_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function the_conference_update_admin_notice(){
    if ( isset( $_GET['the_conference_admin_notice'] ) && $_GET['the_conference_admin_notice'] = '1' ) {
        update_option( 'the_conference_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'the_conference_update_admin_notice' );

if( ! function_exists( 'the_conference_get_page_id_by_template' ) ) :
/**
 * Returns Page ID by Page Template
*/
function the_conference_get_page_id_by_template( $template_name ){
    $args = array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => $template_name
    );
    return get_pages( $args );    
}
endif;
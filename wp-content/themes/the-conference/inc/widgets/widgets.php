<?php
/**
 * The Conference Widget Areas
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package The Conference
 */

function the_conference_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'the-conference' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'the-conference' ),
        ),
        'about' => array(
            'name'        => __( 'About Section', 'the-conference' ),
            'id'          => 'about', 
            'description' => __( 'Add "Rara: A Featured Page Widget" for about section.', 'the-conference' ),
        ),
        'stat-counter' => array(
            'name'        => __( 'Stat Counter Section', 'the-conference' ),
            'id'          => 'stat-counter', 
            'description' => __( 'Add "Rara: Stat Counter Widget" for Stat Counter section.', 'the-conference' ),
        ),
        'recent-conference' => array(
            'name'        => __( 'Recent Conferences Section', 'the-conference' ),
            'id'          => 'recent-conference', 
            'description' => __( 'Add "Rara: Icon Text" Widget for Recent Conferences section.', 'the-conference' ),
        ),
        'speakers' => array(
            'name'        => __( 'Speakers Section', 'the-conference' ),
            'id'          => 'speakers', 
            'description' => __( 'Add "Text" widget for title and description, Add "Rara: Team Member" Widget for Speakers.', 'the-conference' ),
        ),
        'testimonial' => array(
            'name'        => __( 'Testimonial Section', 'the-conference' ),
            'id'          => 'testimonial', 
            'description' => __( 'Add "Text" widget for title and description, Add "Rara: Testimonial" widget for testimonial section.', 'the-conference' ),
        ),
        'cta' => array(
            'name'        => __( 'Call To Action Section', 'the-conference' ),
            'id'          => 'cta', 
            'description' => __( 'Add "Rara: Call To Action" widget for Call to Action section.', 'the-conference' ),
        ),
        'contact' => array(
            'name'        => __( 'Contact Section', 'the-conference' ),
            'id'          => 'contact', 
            'description' => __( 'Add "Text" widget for title and description, Add "Text" widget ( use shortcode ) to display contact form and Add "Rara: Contact Widget" Widget for contact details and social links.', 'the-conference' ),
        ),
        'gmap' => array(
            'name'        => __( 'Google Map Section', 'the-conference' ),
            'id'          => 'gmap', 
            'description' => __( 'Add "Custom HTML" widget for Google Map section.', 'the-conference' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'the-conference' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'the-conference' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'the-conference' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'the-conference' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'the-conference' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'the-conference' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'the-conference' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'the-conference' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }
}
add_action( 'widgets_init', 'the_conference_widgets_init' );
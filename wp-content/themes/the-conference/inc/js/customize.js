( function( api ) {

    // Extends our custom "example-1" section.
    api.sectionConstructor['pro-section'] = api.Section.extend( {

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );

} )( wp.customize );

jQuery(document).ready(function($){
    /* Move Fornt page widgets to frontpage panel */
	wp.customize.section( 'sidebar-widgets-about' ).panel( 'frontpage_settings' );
	wp.customize.section( 'sidebar-widgets-about' ).priority( '20' );
    wp.customize.section( 'sidebar-widgets-stat-counter' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-stat-counter' ).priority( '25' );
    wp.customize.section( 'sidebar-widgets-recent-conference' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-recent-conference' ).priority( '30' );
    wp.customize.section( 'sidebar-widgets-speakers' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-speakers' ).priority( '35' );
    wp.customize.section( 'sidebar-widgets-testimonial' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-testimonial' ).priority( '50' );    
    wp.customize.section( 'sidebar-widgets-cta' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-cta' ).priority( '65' );
    wp.customize.section( 'sidebar-widgets-contact' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-contact' ).priority( '80' );
    wp.customize.section( 'sidebar-widgets-gmap' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-gmap' ).priority( '85' );

    //Scroll to front page section
    $('body').on('click', '#sub-accordion-panel-frontpage_settings .control-subsection .accordion-section-title', function(event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        scrollToSection( section_id );
    }); 
});

function scrollToSection( section_id ){
    var preview_section_id = "banner_section";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {

        case 'accordion-section-sidebar-widgets-about':
        preview_section_id = "about_section";
        break;
        
        case 'accordion-section-sidebar-widgets-stat-counter':
        preview_section_id = "stat-counter_section";
        break;

        case 'accordion-section-sidebar-widgets-recent-conference':
        preview_section_id = "recent-the_conference_section";
        break;
        
        case 'accordion-section-sidebar-widgets-speakers':
        preview_section_id = "speakers_section";
        break;

        case 'accordion-section-sidebar-widgets-testimonial':
        preview_section_id = "testimonial_section";
        break;

        case 'accordion-section-sidebar-widgets-cta':
        preview_section_id = "cta_section";
        break;

        case 'accordion-section-blog_section':
        preview_section_id = "blog_section";
        break;

        case 'accordion-section-sidebar-widgets-contact':
        preview_section_id = "contact_section";
        break;

        case 'accordion-section-sidebar-widgets-gmap':
        preview_section_id = "map_section";
        break;
    }

    if( $contents.find('#'+preview_section_id).length > 0 && $contents.find('.home').length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + preview_section_id ).offset().top
        }, 1000);
    }
}
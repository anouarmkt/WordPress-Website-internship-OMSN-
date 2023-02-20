<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package The Conference
 */
    /**
     * Doctype Hook
     * 
     * @hooked the_conference_doctype
    */
    do_action( 'the_conference_doctype' );
    ?>
    <head itemscope itemtype="https://schema.org/WebSite">
       <?php 
    /**
     * Before wp_head
     * 
     * @hooked the_conference_head
    */
    do_action( 'the_conference_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
    
    <?php
    wp_body_open();
    /**
     * Before Header
     * 
     * @hooked the_conference_page_start - 20 
    */
    do_action( 'the_conference_before_header' );
    
    /**
     * Header
     * 
     * @hooked the_conference_header - 20     
    */
    do_action( 'the_conference_header' );

     /**
     * Before Content
     * 
     * @hooked the_conference_banner - 15
    */
     do_action( 'the_conference_after_header' );
     
    /**
     * Content
     * 
     * @hooked the_conference_content_start
    */
    do_action( 'the_conference_content' );
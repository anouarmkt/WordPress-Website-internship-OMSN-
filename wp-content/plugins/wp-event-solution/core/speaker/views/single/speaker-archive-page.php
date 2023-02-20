

<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

?>

<?php get_header();?>

<?php

//check if single page template is overriden from theme
//if overriden, then the overriden template has higher priority
if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'archive-speaker.php' ) ) {
    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'archive-speaker.php';
} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'archive-speaker.php' ) ) {
    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'archive-speaker.php';
} elseif ( file_exists( \Wpeventin::templates_dir() . 'archive-speaker.php' ) ) {
    include_once \Wpeventin::templates_dir() . 'archive-speaker.php';
}

?>

<?php get_footer();?>
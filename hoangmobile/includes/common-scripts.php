<?php
/* ----------------------------------------------------------------------------------- */
# Register main Scripts and Styles
/* ----------------------------------------------------------------------------------- */
add_action('admin_enqueue_scripts', 'tie_register_scripts');
//add_action('wp_enqueue_scripts', 'tie_register_scripts');

function tie_register_scripts() {
    wp_enqueue_media();
    
    ## Register All Scripts
    wp_register_script('tie-scripts', get_template_directory_uri() . '/js/adminjs/scripts.js', array('jquery'));
    //wp_register_script('tie-tabs', get_template_directory_uri() . '/js/tabs.min.js', array('jquery'));

    ## Register All Styles
    //wp_register_style('page-template', get_template_directory_uri() . '/css/page-template.css', array(), '', 'all');

    ## Get Global Scripts
    wp_enqueue_script('tie-scripts');
}
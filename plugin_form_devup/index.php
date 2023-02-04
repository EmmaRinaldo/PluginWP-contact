<?php
/*
Plugin Name: Extension formulaire
Version: 1.0
Description: Ceci est un plugin de formulaire
Author: Dev'Up
*/



if ( ! defined( 'WPINC' ) ) {
    die;
}

register_activation_hook(__FILE__,'wf_add_option');

function wf_add_option(){

    wf_create_subscribers_table();

}

require_once( 'functions.php' );

function add_menu_tab(){
    add_menu_page(
        "Formulaire Dev'Up",
        "Mes Clients",
        "manage_options",
        "plugin-form",
        "get_html_page"
    );
    add_submenu_page(
        "plugin-form",
        "Ajouter Client",
        "Ajouter",
        "manage_options",
        "plugin-form-add",
        "get_add_form"
    );
}
add_action("admin_menu", "add_menu_tab");



// visuel shortcode

add_shortcode( 'devuptutorials','wf_hello_world');









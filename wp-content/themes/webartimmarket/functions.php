<?php
/**
 * Webartimmerket engine room
 *
 * @package webartimmarket
 */


/**
* Enqueue scripts
*/

function dc_scripts() {
    
    //styles
    wp_register_style( 'responsive', get_template_directory_uri() . '/responsive.css', array(), '1.0.0');
    wp_enqueue_style( 'responsive' );
    wp_register_style( 'font-awesome', get_template_directory_uri() . '/style/font-awesome-4.7.0/css/font-awesome.min.css', array(), '4.7.0');
    wp_enqueue_style( 'font-awesome' );
    wp_register_style( 'fonts-roboto', get_template_directory_uri() . '/fonts/roboto.css', array(), '1.0.0');
    wp_enqueue_style( 'fonts-roboto' );

    //scripts
    wp_register_script( 'core_script', get_template_directory_uri() . '/js/core.js', array( 'jquery' ));
	wp_enqueue_script( 'core_script' );
}    
add_action( 'wp_enqueue_scripts', 'dc_scripts' );
/* end Enqueue scripts */

/**
 * Custom theme setting
 */
require get_template_directory() . '/inc/theme-setting.php';

/* end Custom theme setting */

/**
* Woocommerce
*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

/**
* Menu
*/

add_theme_support('menus');
class menuWalker extends Walker_Nav_Menu {
	function end_el(&$output, $item, $depth = 0, $args = array()) {
		$output .= "</li>";
	}
}

add_action( 'after_setup_theme', 'register_menu' );
function register_menu() {
    // register_nav_menu( 'header_top', 'Меню над шапкой' );
    // register_nav_menu( 'header_bottom', 'Меню под шапкой' );
    // register_nav_menu( 'footer', 'Меню в подвале' );
    // register_nav_menu( 'phone_menu', 'Мобильное' );
}
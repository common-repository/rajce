<?php

/*
 * Plugin Name:       Rajce
 * Plugin URI:        http://www.venca-x.cz
 * Description:       Plugin pro zobrazení galerií z http://www.rajce.idnes.cz
 * Version:           0.4.2
 * Author:            vEnCa-X
 * Author URI:        http://www.venca-x.cz
 * License:           MIT
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Include bootstrap
 */
require_once plugin_dir_path( __FILE__ ) . 'WPFW/bootstrap.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/rajce_widget.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/widget.php';
require_once plugin_dir_path( __FILE__ ) . 'factory/RajceFactory.php';


// [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015/"]
function rajceShortcode( $atts ) {
    $attsArray = shortcode_atts( array(
        'url' => NULL,
        'size' => NULL,
    ), $atts );

    $return = "<div class=\"rajce-gallery\">";

    if($attsArray['url'] != "") {
        $return.= RajceFactory::getRajceContent($attsArray);
    }

    $return.= "</div>";

    return $return;
}
add_shortcode( 'rajce', 'rajceShortcode' );

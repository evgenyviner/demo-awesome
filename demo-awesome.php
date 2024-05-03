<?php
/**
 * @link              https://theme4press.com/demo-awesome-the-data-importer/
 * @since             1.0.0
 * @package           Demo Awesome
 * @author            Theme4Press
 *
 * @wordpress-plugin
 * Plugin Name:       Demo Awesome
 * Plugin URI:        https://theme4press.com/demo-awesome-the-data-importer/
 * Description:       Import the Theme4Press theme demo content including theme settings, menus, widgets, sliders, and much more with just one click. Awesome!
 * Version:           1.0.3
 * Author:            Theme4Press
 * Author URI:        https://theme4press.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       demo-awesome
 * Domain Path:       /languages
 */

// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * @since    1.0.0
 */
define( 'DEMO_AWESOME_VERSION', '1.0.0' );

/**
 * The class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks
 */
require plugin_dir_path( __FILE__ ) . 'inc/class-demo-awesome.php';

/**
 * @since    1.0.0
 */
if ( ! function_exists( 'demo_awesome_run' ) ) {
	function demo_awesome_run() {
		$plugin = new Demo_Awesome();
		$plugin->run();
	}
}
// Allow SVG in wp_kses_post()
function demo_awesome_allow_svg_in_wp_kses_post( $allowed_post_tags ) {
    $allowed_post_tags['svg'] = array(
        'xmlns' => true,
        'width' => true,
        'height' => true,
        'viewBox' => true,
        'fill' => true,
        'class' => true,
        'aria-hidden' => true,
        'role' => true,
        'focusable' => true,
        'style' => true,
    );
    $allowed_post_tags['path'] = array(
        'd' => true,
        'fill' => true,
    );
    return $allowed_post_tags;
}
add_filter( 'wp_kses_allowed_html', 'demo_awesome_allow_svg_in_wp_kses_post', 10, 1 );


demo_awesome_run();
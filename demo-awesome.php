<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://theme4press.com/demo-awesome-importer
 * @since             1.0.0
 * @package           Demo Awesome
 *
 * @wordpress-plugin
 * Plugin Name:       Demo Awesome
 * Plugin URI:        https://theme4press.com/demo-awesome-importer
 * Description:       Start building beautiful websites using the Demo Awesome plugin with the easy-to-use drag & drop system which will help you to add any component - if it's a Gutenberg block, Bootstrap Component or any regular widget
 * Version:           1.0.0
 * Author:            Theme4Press
 * Author URI:        https://theme4press.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       demo-awesome
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in inc/class-demo-awesome-activator.php
 */
function demo_awesome_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-demo-awesome-activator.php';
	Demo_Awesome_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in inc/class-demo-awesome-deactivator.php
 */
function demo_awesome_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/class-demo-awesome-deactivator.php';
	Demo_Awesome_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'demo_awesome_activate' );
register_deactivation_hook( __FILE__, 'demo_awesome_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'inc/class-demo-awesome.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function demo_awesome_run() {

	$plugin = new Demo_Awesome();
	$plugin->run();

}

demo_awesome_run();
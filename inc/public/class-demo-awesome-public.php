<?php

/**
 * The admin functionality of the plugin
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if ( ! class_exists( 'Demo_Awesome_Public' ) ) {
	class Demo_Awesome_Public {

		/**
		 * @since    1.0.0
		 */
		private $plugin_name;

		/**
		 * @since    1.0.0
		 */
		private $version;

		/**
		 * @since    1.0.0
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'inc/public/css/public.css', array(), $this->version, 'all' );

		}

		/**
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'inc/public/js/public.js', array( 'jquery' ), $this->version, false );

		}
	}
}
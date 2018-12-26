<?php
/**
 * The plugin core class definition
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if ( ! class_exists( 'Demo_Awesome' ) ) {
	class Demo_Awesome {

		/**
		 * @since    1.0.0
		 */
		protected $loader;

		/**
		 * @since    1.0.0
		 */
		protected $plugin_name;

		/**
		 * @since    1.0.0
		 */
		protected $version;

		/**
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'DEMO_AWESOME_VERSION' ) ) {
				$this->version = DEMO_AWESOME_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'demo-awesome';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();

		}

		/**
		 * @since    1.0.0
		 */
		private function load_dependencies() {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-demo-awesome-loader.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-demo-awesome-i18n.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/admin/class-demo-awesome-admin.php';

			$this->loader = new Demo_Awesome_Loader();

		}

		/**
		 * @since    1.0.0
		 */
		private function set_locale() {

			$plugin_i18n = new Demo_Awesome_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * @since    1.0.0
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Demo_Awesome_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		}

		/**
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * @since     1.0.0
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * @since     1.0.0
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * @since     1.0.0
		 */
		public function get_version() {
			return $this->version;
		}
	}
}
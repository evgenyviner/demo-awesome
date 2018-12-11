<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Demo Awesome
 * @subpackage demo-awesome/admin
 * @author     Theme4Press
 */

if( !defined( 'EVOLVE_IMPORTER_DIR' ) ){
	define( 'EVOLVE_IMPORTER_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! class_exists( 'Demo_Awesome_Admin' ) ) {
	class Demo_Awesome_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 *
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			add_action( 'admin_menu', array( $this, 'demo_awesome_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'demo_awesome_scripts' ) );
			add_action( 'wp_ajax_call_import_function_from_ajax', array($this, 'call_import_function_from_ajax' ));
		}

		function get_demo_packages($url, $template_name = '') {
			$packages = get_transient( 'demo_awesome_importer_packages_'.$template_name );

			$raw_packages = wp_safe_remote_get( $url );

			if ( ! is_wp_error( $raw_packages ) ) {
				$packages = json_decode( wp_remote_retrieve_body( $raw_packages ) );

				if ( $packages ) {
					set_transient( 'demo_awesome_importer_packages_'.$template_name, $packages, WEEK_IN_SECONDS );
				}
			}
			return $packages;
		}

		function get_import_file_content($template_name){
			return $this->get_demo_packages("https://demo.theme4press.com/demo-import/".$template_name."/evolve.wordpress.xml", $template_name);
		}

		function get_import_file_path($template_name){
			return $this->write_file_to_local($this->get_import_file_content($template_name));
		}

		function write_file_to_local( $file_content ) {

			global $wp_filesystem;
			// Initialize the WP filesystem, no more using 'file-put-contents' function
			if ( empty( $wp_filesystem ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$theme_path = str_replace( ABSPATH, $wp_filesystem->abspath(), EVOLVE_IMPORTER_DIR );
			$result = $wp_filesystem->put_contents(
				$theme_path . 'dummy-data.xml',
				$file_content,
				FS_CHMOD_FILE // predefined mode settings for WP files
			);
			return $theme_path . 'dummy-data.xml';
		}

		function call_import_function_from_ajax() {
			$import_file = $this->get_import_file_path( 'blog' );

			// Load Importer API.
			require_once ABSPATH . 'wp-admin/includes/import.php';

			if ( ! class_exists( 'WP_Importer' ) ) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

				if ( file_exists( $class_wp_importer ) ) {
					require $class_wp_importer;
				}
			}

			// Include WXR Importer.
			require dirname( __FILE__ ) . '/wordpress-importer/class-wxr-importer.php';

			if ( is_file( $import_file ) ) {
				$wp_import = new DA_WXR_Importer();
				$wp_import->fetch_attachments = true;

				ob_start();
				$wp_import->import( $import_file );
				ob_end_clean();

				flush_rewrite_rules();
			} else {
				$status['errorMessage'] = __( 'The XML file dummy content is missing.', 'demo-awesome' );
				wp_send_json_error( $status );
			}

			wp_die(); // this is required to terminate immediately and return a proper response
		}

		function demo_awesome_page() {
			add_theme_page( __( 'Demo Awesome Importer', 'demo-awesome' ), __( 'Demo Awesome', 'demo-awesome' ), 'edit_theme_options', 'demo-awesome-importer', array(
				$this,
				'demo_awesome_browser'
			) );
		}

		function demo_awesome_scripts( $hook ) {
			if ( 'appearance_page_demo-awesome-importer' != $hook ) {
				return;
			}
			wp_enqueue_style( 'demo-awesome', plugin_dir_url( __FILE__ ) . '/css/demo-awesome-admin.min.css' );
			wp_enqueue_script( 'demo-awesome', plugin_dir_url( __FILE__ ) . '/js/demo-awesome-admin.js' );

			// Add Defined Local Variables

			$local_variables = array(
				'close_button'  => __( 'Close', 'demo-awesome' ),
				'back_button'   => __( 'Back', 'demo-awesome' ),
				'next_button'   => __( 'Next', 'demo-awesome' ),
				'import_button' => __( 'Begin Import', 'demo-awesome' )
			);

			wp_localize_script( 'demo-awesome', 'demo_awesome_js_local_vars', $local_variables );
		}

		function demo_awesome_browser() { ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php echo esc_html( __( 'Demo Awesome Importer', 'demo-awesome' ) ); ?></h1>

                <hr class="wp-header-end">
				<?php require plugin_dir_path( __FILE__ ) . '/demo-browser.php'; ?>
            </div>

		<?php }

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Demo_Awesome_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Demo_Awesome_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demo-awesome-admin.min.css', array(), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Demo_Awesome_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Demo_Awesome_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demo-awesome-admin.js', array( 'jquery' ), $this->version, false );
		}
	}
}
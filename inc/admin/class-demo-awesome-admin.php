<?php

/**
 * The admin functionality of the plugin
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if ( ! defined( 'DEMO_AWESOME_IMPORTER_DIRECTORY' ) ) {
	define( 'DEMO_AWESOME_IMPORTER_DIRECTORY', plugin_dir_path( __FILE__ ) );
}

if ( ! class_exists( 'Demo_Awesome_Admin' ) ) {
	class Demo_Awesome_Admin {

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

			add_action( 'admin_menu', array( $this, 'importer_page' ) );
			add_action( 'wp_ajax_call_import_function_from_ajax', array( $this, 'call_import_function_from_ajax' ) );
		}

		/**
		 * @since    1.0.0
		 */
		function get_list_demos() {
			$demo_awesome_get_list_demos = $this->get_demo_packages( 'https://demo.theme4press.com/demo-import/get-list-demos.json', 'get_list_demos' );

			return $demo_awesome_get_list_demos;
		}

		/**
		 * @since    1.0.0
		 */
		function get_demo_packages( $url, $template_name = '' ) {
			if ( true || false === ( $packages = get_transient( 'demo_awesome_importer_packages_' . $template_name ) ) ) {
				$raw_packages = wp_safe_remote_get( $url );
				if ( ! is_wp_error( $raw_packages ) ) {
					$packages = wp_remote_retrieve_body( $raw_packages );
					if ( $packages ) {
						set_transient( 'demo_awesome_importer_packages_' . $template_name, $packages, HOUR_IN_SECONDS );
					}
				}
			}

			return $packages;
		}

		/**
		 * @since    1.0.0
		 */
		function get_import_file_content( $template_name ) {
			return $this->get_demo_packages( "https://demo.theme4press.com/demo-import/" . $template_name . "/evolve.wordpress.xml", $template_name );
		}

		/**
		 * @since    1.0.0
		 */
		function get_import_file_path( $template_name ) {
			return $this->write_file_to_local( $this->get_import_file_content( $template_name ) );
		}

		/**
		 * @since    1.0.0
		 */
		function write_file_to_local( $file_content ) {

			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$theme_path = str_replace( ABSPATH, $wp_filesystem->abspath(), DEMO_AWESOME_IMPORTER_DIRECTORY );
			$result     = $wp_filesystem->put_contents(
				$theme_path . '/demo-content/dummy-data.xml',
				$file_content,
				FS_CHMOD_FILE
			);

			return $theme_path . '/demo-content/dummy-data.xml';
		}

		/**
		 * @since    1.0.0
		 */
		function call_import_function_from_ajax() {

			$import_file = $this->get_import_file_path( 'blog' );

			require_once ABSPATH . 'wp-admin/includes/import.php';

			if ( ! class_exists( 'WP_Importer' ) ) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

				if ( file_exists( $class_wp_importer ) ) {
					require $class_wp_importer;
				}
			}

			// Include class demo awesome importer
			require dirname( __FILE__ ) . '/importer/class-demo-awesome-importer.php';

			if ( is_file( $import_file ) ) {
				$wp_import                    = new Demo_Awesome_Importer();
				$wp_import->fetch_attachments = true;

				ob_start();
				$wp_import->import( $import_file );
				ob_end_clean();

				flush_rewrite_rules();
			} else {
				$status['errorMessage'] = __( 'The XML file dummy content is missing', 'demo-awesome' );
				wp_send_json_error( $status );
			}

			wp_die();
		}

		/**
		 * @since    1.0.0
		 */
		function importer_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			add_theme_page( __( 'Demo Awesome - The Data Importer', 'demo-awesome' ), __( 'Demo Awesome', 'demo-awesome' ), 'edit_theme_options', 'demo-awesome-importer', array(
				$this,
				'demo_browser'
			) );
		}

		/**
		 * @since    1.0.0
		 */
		function demo_browser() { ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php echo esc_html( __( 'Demo Awesome - The Data Importer', 'demo-awesome' ) ); ?></h1>

                <hr class="wp-header-end">
				<?php
				$demo_awesome_get_list_demos = $this->get_list_demos();
				require plugin_dir_path( __FILE__ ) . '/demo-browser.php';
				?>
            </div>

		<?php }

		/**
		 * @since    1.0.0
		 */
		public function enqueue_styles( $hook ) {

			if ( 'appearance_page_demo-awesome-importer' != $hook ) {
				return;
			}

			wp_enqueue_style( 'demo-awesome', plugin_dir_url( __FILE__ ) . '/css/admin.css' );

		}

		/**
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {

			if ( 'appearance_page_demo-awesome-importer' != $hook ) {
				return;
			}

			wp_enqueue_script( 'demo-awesome', plugin_dir_url( __FILE__ ) . '/js/admin.js' );

			$local_variables = array(
				'close_button'  => __( 'Close', 'demo-awesome' ),
				'back_button'   => __( 'Back', 'demo-awesome' ),
				'next_button'   => __( 'Next', 'demo-awesome' ),
				'import_button' => __( 'Begin Import', 'demo-awesome' )
			);

			wp_localize_script( 'demo-awesome', 'demo_awesome_js_local_vars', $local_variables );

		}
	}
}
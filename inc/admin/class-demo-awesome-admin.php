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
if ( ! defined( 'DEMO_AWESOME_IMPORTER_SOURCE_URL' ) ) {
	define( 'DEMO_AWESOME_IMPORTER_SOURCE_URL', 'https://demo.theme4press.com/demo-import/' );
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
			add_action( 'wp_ajax_required_plugins', array( $this, 'required_plugins' ) );
			add_filter( 'customizer_demo_import_settings', array(
				$this,
				'update_customizer_data'
			), 10, 2 );
			add_filter( 'widget_demo_import_settings', array( $this, 'update_widget_data' ), 10, 4 );

			if ( isset( $_GET['hide-notice'] ) && $_GET['hide-notice'] == 'demo_awesome_notice' ) {
				update_option( 'demo_awesome_notice', 0 );
			}
			if ( get_option( 'demo_awesome_notice', 1 ) ) {
				add_action( 'admin_notices', array( $this, 'no_theme4press_theme_notice' ) );
			}
		}

		/**
		 * @since    1.0.0
		 */
		function get_list_demos() {
			$demo_awesome_get_list_demos = $this->get_demo_packages( DEMO_AWESOME_IMPORTER_SOURCE_URL . 'get-list-demos.json', 'get_list_demos' );

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
			return $this->get_demo_packages( DEMO_AWESOME_IMPORTER_SOURCE_URL . $template_name . "/content.xml", $template_name );
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
		function write_xml_file_to_local( $file_content ) {
			return $this->write_file_to_local( $file_content );
		}

		/**
		 * @since    1.0.0
		 */
		function write_file_to_local( $file_content, $file_name = 'dummy-data.xml' ) {

			global $wp_filesystem;
			// Initialize the WP filesystem, no more using 'file-put-contents' function
			if ( empty( $wp_filesystem ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$file_path = wp_upload_dir()['basedir'] . '/demo-awesome-import/' . $file_name;
			$result    = $wp_filesystem->put_contents(
				$file_path,
				$file_content,
				FS_CHMOD_FILE // predefined mode settings for WP files
			);

			return $file_path;
		}

		/**
		 * @since    1.0.0
		 */
		function call_import_function_from_ajax() {

			$data_demo = isset( $_REQUEST['data_demo'] ) ? $_REQUEST['data_demo'] : array();

			$template_name = isset( $data_demo['folder_path'] ) ? $data_demo['folder_path'] : '';

			delete_option( 'theme_mods_evolve-plus' );
			delete_option( 'theme_mods_evolve' );

			if ( Demo_Awesome_Admin::is_premium_theme() == true && ! $data_demo['premium_demo'] ) {
				update_option( 'check_updated_to_new_bootstrap_slider_data_', false );
				update_option( 'check_updated_to_new_parallax_slider_data_', false );
				update_option( 'check_updated_to_new_content_boxes_data_', false );
				update_option( 'check_updated_to_new_testimonials_data_', false );
				update_option( 'check_updated_to_new_counter_circle_data_', false );
			}

			if ( Demo_Awesome_Admin::is_premium_theme() == false && $data_demo['premium_demo'] ) {
				wp_send_json_success( array(
					'success' => true,
					'message' => sprintf( '<span>%s</span>', __( 'The premium demo need premium theme version', 'demo-awesome' ) )
				) );
				wp_die();
			}
			//import content data
			$this->import_content_theme( $data_demo, $template_name );
			//import widget
			$this->import_widget_settings( $data_demo, $template_name );
			//import customizer
			$this->import_customizer_data( $data_demo, $template_name );
			//fix menu
			$this->update_nav_menu_items( $data_demo, $template_name );
			//fix option
			$this->update_option_data( $data_demo, $template_name );

			wp_send_json_success( array(
				'success' => true,
				'message' => sprintf( __( '<div>%1$s<span class="mr-1">%2$s</span>%3$s%4$s%5$s</div>' ), '<h3>', Demo_Awesome_Admin::get_svg( 'check' ), __( 'Import finished', 'demo-awesome' ), '</h3>', __( 'The demo has been imported successfully', 'demo-awesome' ) )
			) );

			wp_die(); // this is required to terminate immediately and return a proper response
		}

		/**
		 * @since    1.0.0
		 */
		function required_plugins() {

			// Include the required plugins list
			require dirname( __FILE__ ) . '/required-plugins.php';
			$data_demo = isset( $_REQUEST['data_demo'] ) ? $_REQUEST['data_demo'] : array();
			demo_awesome_required_plugins( $data_demo );
			wp_die(); // this is required to terminate immediately and return a proper response
		}

		/**
		 * @since    1.0.0
		 */
		function get_import_file_path_from_live_demo( $template_name, $file_name ) {
			return $this->write_file_to_local( $this->get_demo_packages( DEMO_AWESOME_IMPORTER_SOURCE_URL . $template_name . '/' . $file_name ), $file_name );
		}

		/**
		 * @since    1.0.0
		 */
		function update_option_data( $data_demo, $template_name = 'blog' ) {
			if ( ! empty( $data_demo['option_update'] ) ) {
				foreach ( $data_demo['option_update'] as $data_type => $data_value ) {
					if ( $data_type == 'update_pages' && $data_value && is_array( $data_value ) && count( $data_value ) ) {
						foreach ( $data_value as $option_name => $option_value ) {
							$page = get_page_by_title( $option_value );
							if ( is_object( $page ) && $page->ID ) {
								update_option( $option_name, $page->ID );
							}
						}
					} else {
						update_option( $data_type, $data_value );
					}
				}
			}
		}

		/**
		 * @since    1.0.0
		 */
		public function import_content_theme( $data_demo, $template_name = 'blog' ) {

			$import_file = $this->get_import_file_path( $template_name );

			// Load Importer API.
			require_once ABSPATH . 'wp-admin/includes/import.php';

			if ( ! class_exists( 'WP_Importer' ) ) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

				if ( file_exists( $class_wp_importer ) ) {
					require $class_wp_importer;
				}
			}

			// Include Class Demo Awesome Importer
			require dirname( __FILE__ ) . '/importer/class-demo-awesome-importer.php';

			if ( is_file( $import_file ) ) {
				$wp_import                    = new Demo_Awesome_Importer();
				$wp_import->fetch_attachments = true;

				ob_start();
				$wp_import->import( $import_file );
				ob_end_clean();

				flush_rewrite_rules();
			} else {
				$status['errorMessage'] = __( 'The XML file dummy content is missing.', 'demo-awesome' );
				wp_send_json_error( $status );
			}

			return true;
		}

		/**
		 * @since    1.0.0
		 */
		function import_widget_settings( $data_demo, $template_name = 'blog' ) {

			require dirname( __FILE__ ) . '/importer/class-demo-awesome-widget-importer.php';

			$import_file = $this->get_import_file_path_from_live_demo( $template_name, 'widgets.wie' );

			if ( is_file( $import_file ) ) {
				$results = Demo_Awesome_Widget_Importer::import( $import_file, $data_demo );

				if ( is_wp_error( $results ) ) {
					return false;
				}
			} else {
				$status['errorMessage'] = __( 'The WIE file widget content is missing.', 'demo-awesome' );
				wp_send_json_error( $status );
			}

			return true;
		}

		/**
		 * @since    1.0.0
		 */
		function import_customizer_data( $data_demo, $template_name = 'blog' ) {
			require dirname( __FILE__ ) . '/importer/class-demo-awesome-customizer-importer.php';

			$import_file = $this->get_import_file_path_from_live_demo( $template_name, 'customizer.dat' );

			if ( is_file( $import_file ) ) {
				$results = Demo_Awesome_Customizer_Importer::import( $import_file, $data_demo );

				if ( is_wp_error( $results ) ) {
					return false;
				}
			} else {
				$status['errorMessage'] = __( 'The DAT file customizer data is missing.', 'demo-awesome' );
				wp_send_json_error( $status );
			}

			return true;
		}

		/**
		 * @since    1.0.0
		 */
		function update_nav_menu_items() {
			$menu_locations = get_nav_menu_locations();

			foreach ( $menu_locations as $location => $menu_id ) {

				if ( is_nav_menu( $menu_id ) ) {
					$menu_items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );

					if ( ! empty( $menu_items ) ) {
						foreach ( $menu_items as $menu_item ) {
							if ( isset( $menu_item->url ) && isset( $menu_item->db_id ) && 'custom' == $menu_item->type ) {
								$site_parts = parse_url( home_url( '/' ) );
								$menu_parts = parse_url( $menu_item->url );

								// Update existing custom nav menu item URL.
								if ( isset( $menu_parts['path'] ) && isset( $menu_parts['host'] ) && apply_filters( 'demo_awesome_importer_nav_menu_item_url_hosts', in_array( $menu_parts['host'], array( 'demo.theme4press.com' ) ) ) ) {
									$menu_item->url = str_replace( array(
										$menu_parts['scheme'],
										$menu_parts['host'],
										$menu_parts['path']
									), array(
										$site_parts['scheme'],
										$site_parts['host'],
										trailingslashit( $site_parts['path'] )
									), $menu_item->url );
									update_post_meta( $menu_item->db_id, '_menu_item_url', esc_url_raw( $menu_item->url ) );
								}
							}
						}
					}
				}
			}
		}

		/**
		 * @since    1.0.0
		 */
		function update_customizer_data( $data, $demo_data = array() ) {
			if ( empty( $demo_data['customizer_data_update'] ) ) {
				$demo_data['customizer_data_update']['nav_menu_locations'] = array(
					'primary-menu'      => 'Main menu',
					'sticky_navigation' => 'Main menu',
				);
			}
			if ( ! empty( $demo_data['customizer_data_update'] ) ) {
				foreach ( $demo_data['customizer_data_update'] as $data_type => $data_value ) {
					if ( ! in_array( $data_type, array( 'pages', 'categories', 'nav_menu_locations' ) ) ) {
						continue;
					}

					// Format the value based on data type.
					switch ( $data_type ) {
						case 'pages':
							foreach ( $data_value as $option_key => $option_value ) {
								if ( ! empty( $data['mods'][ $option_key ] ) ) {
									$page = get_page_by_title( $option_value );

									if ( is_object( $page ) && $page->ID ) {
										$data['mods'][ $option_key ] = $page->ID;
									}
								}
							}
							break;
						case 'categories':
							foreach ( $data_value as $taxonomy => $taxonomy_data ) {
								if ( ! taxonomy_exists( $taxonomy ) ) {
									continue;
								}

								foreach ( $taxonomy_data as $option_key => $option_value ) {
									if ( ! empty( $data['mods'][ $option_key ] ) ) {
										$term = get_term_by( 'name', $option_value, $taxonomy );

										if ( is_object( $term ) && $term->term_id ) {
											$data['mods'][ $option_key ] = $term->term_id;
										}
									}
								}
							}
							break;
						case 'nav_menu_locations':
							$nav_menus = wp_get_nav_menus();

							if ( ! empty( $nav_menus ) ) {
								foreach ( $nav_menus as $nav_menu ) {
									if ( is_object( $nav_menu ) ) {
										foreach ( $data_value as $location => $location_name ) {
											if ( $nav_menu->name == $location_name ) {
												$data['mods'][ $data_type ][ $location ] = $nav_menu->term_id;
											}
										}
									}
								}
							}
							break;
					}
				}
			}

			return $data;
		}

		/**
		 * @since    1.0.0
		 */
		function update_widget_data( $widget, $widget_type, $instance_id, $demo_data ) {
			if ( 'nav_menu' == $widget_type ) {
				$nav_menu = wp_get_nav_menu_object( $widget['title'] );

				if ( is_object( $nav_menu ) && $nav_menu->term_id ) {
					$widget['nav_menu'] = $nav_menu->term_id;
				}
			} elseif ( ! empty( $demo_data['widgets_data_update'] ) ) {
				foreach ( $demo_data['widgets_data_update'] as $dropdown_type => $dropdown_data ) {
					if ( ! in_array( $dropdown_type, array( 'dropdown_pages', 'dropdown_categories' ) ) ) {
						continue;
					}

					// Format the value based on dropdown type.
					switch ( $dropdown_type ) {
						case 'dropdown_pages':
							foreach ( $dropdown_data as $widget_id => $widget_data ) {
								if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $widget_type ) {
									foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
										$page = get_page_by_title( $widget_value );

										if ( is_object( $page ) && $page->ID ) {
											$widget[ $widget_key ] = $page->ID;
										}
									}
								}
							}
							break;
						case 'dropdown_categories':
							foreach ( $dropdown_data as $taxonomy => $taxonomy_data ) {
								if ( ! taxonomy_exists( $taxonomy ) ) {
									continue;
								}

								foreach ( $taxonomy_data as $widget_id => $widget_data ) {
									if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $widget_type ) {
										foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
											$term = get_term_by( 'name', $widget_value, $taxonomy );

											if ( is_object( $term ) && $term->term_id ) {
												$widget[ $widget_key ] = $term->term_id;
											}
										}
									}
								}
							}
							break;
					}
				}
			}

			return $widget;
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_theme4press_theme() {
			$demo_awesome_my_theme = wp_get_theme();
			if ( $demo_awesome_my_theme->get( 'Name' ) == 'evolve' || $demo_awesome_my_theme->get( 'Name' ) == 'evolve Child' || $demo_awesome_my_theme->get( 'Name' ) == 'evolve Plus' || $demo_awesome_my_theme->get( 'Name' ) == 'evolve Plus Child' ) {
				return true;
			}

			return false;
		}

		/**
		 * @since    1.0.0
		 */
		public function no_theme4press_theme_notice() {
			if ( Demo_Awesome_Admin::is_theme4press_theme() ) {
				return;
			}

			wp_enqueue_style( 'demo-awesome-notice', plugin_dir_url( __FILE__ ) . 'css/notice.css' ); ?>

            <div class="notice demo-awesome-notice is-dismissible">
                <p><?php echo Demo_Awesome_Admin::is_theme4press_theme_message(); ?><a
                            href="<?php echo esc_url( add_query_arg( 'hide-notice', 'demo_awesome_notice' ) ); ?>"><?php _e( 'Dismiss', 'demo-awesome' ); ?></a>
                </p>
            </div>
			<?php
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_theme4press_theme_message() {
			$message = '';
			if ( ! Demo_Awesome_Admin::is_theme4press_theme() ) {
				$message = "<span><img src='" . plugin_dir_url( __FILE__ ) . "images/logo.png' />" . sprintf( __( 'The %1$sDemo Awesome%2$s plugin is designed only for %3$sTheme4Press%4$s themes', 'demo-awesome' ), '<strong>', '</strong>', '<strong>', '</strong>' ) . "</span><a class='button button-primary' target='_blank' href='" . get_admin_url() . "theme-install.php?search=theme4press" . "'>" . __( 'Install theme', 'demo-awesome' ) . "</a>";
			}

			return $message;
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_free_theme() {
			$demo_awesome_my_theme = wp_get_theme();
			if ( $demo_awesome_my_theme->get( 'Name' ) == 'evolve' || $demo_awesome_my_theme->get( 'Name' ) == 'evolve Child' ) {
				return true;
			}

			return false;
		}

		/**
		 * @since    1.0.0
		 */
		public static function is_premium_theme() {
			$demo_awesome_my_theme = wp_get_theme();
			if ( $demo_awesome_my_theme->get( 'Name' ) == 'evolve Plus' || $demo_awesome_my_theme->get( 'Name' ) == 'evolve Plus Child' ) {
				return true;
			}

			return false;
		}

		/**
		 * @since    1.0.0
		 */
		public static function get_svg( $icon = null ) {

			if ( empty( $icon ) ) {
				return;
			}

			$svg = '<svg class="demo-awesome-icon-' . esc_attr( $icon ) . '" aria-hidden="true" role="img">';
			$svg .= ' <use xlink:href="' . plugin_dir_url( __FILE__ ) . ( '/images/icons.svg#demo-awesome-icon-' ) . esc_html( $icon ) . '"></use> ';
			$svg .= '</svg>';

			return $svg;
		}


		/**
		 * @since    1.0.0
		 */
		public static function premium_url() {
			$premium_url = esc_url( "https://theme4press.com/evolve-multipurpose-wordpress-theme/" );

			return $premium_url;
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

			wp_enqueue_style( 'demo-awesome', plugin_dir_url( __FILE__ ) . 'css/admin.css' );

		}

		/**
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {

			if ( 'appearance_page_demo-awesome-importer' != $hook ) {
				return;
			}

			wp_enqueue_script( 'demo-awesome', plugin_dir_url( __FILE__ ) . 'js/admin.js' );

			$local_variables = array(
				'close_button'         => __( 'Close', 'demo-awesome' ),
				'back_button'          => __( 'Back', 'demo-awesome' ),
				'next_button'          => __( 'Next', 'demo-awesome' ),
				'import_button'        => __( 'Begin Import', 'demo-awesome' ),
				'plugin_url'           => plugin_dir_url( __FILE__ ),
				'website_url'          => get_site_url(),
				'admin_url'            => admin_url(),
				'is_premium_version'   => Demo_Awesome_Admin::is_premium_theme(),
				'is_free_version'      => Demo_Awesome_Admin::is_free_theme(),
				'is_theme4press_theme' => Demo_Awesome_Admin::is_theme4press_theme(),
			);

			wp_localize_script( 'demo-awesome', 'demo_awesome_js_local_vars', $local_variables );

		}
	}
}
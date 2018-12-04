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

			// Theme Demos
			add_action( 'admin_menu', array( $this, 'evolve_demos') );
			add_action( 'admin_enqueue_scripts', array( $this, 'evolve_demo_scripts') );
			

		}
		

		
		function evolve_demos() {
			add_theme_page( __( 'evolve Theme Demo Import', 'evolve' ), __( 'Theme Demo Import', 'evolve' ), 'edit_theme_options', 'evolve-theme-demos', array($this, 'evolve_theme_demos') );

		}

		function evolve_demo_scripts( $hook ) {
			if ( 'appearance_page_evolve-theme-demos' != $hook ) {
				return;
			}
			wp_enqueue_style( 'evolve-demos', plugin_dir_url( __FILE__ ) . '/css/style.css' );
			wp_enqueue_script( 'evolve-demos', plugin_dir_url( __FILE__ ) . '/js/demo-content.min.js' );

			// Add Defined Local Variables

			$local_variables = array(
				'close_button'  => __( 'Close', 'evolve' ),
				'back_button'   => __( 'Back', 'evolve' ),
				'next_button'   => __( 'Next', 'evolve' ),
				'import_button' => __( 'Begin Import', 'evolve' )
			);

			wp_localize_script( 'evolve-demos', 'evolve_js_local_vars', $local_variables );
		}

			

		function evolve_theme_demos() { ?>
	        <div class="wrap">
	            <h1 class="wp-heading-inline"><?php echo esc_html( __( 'evolve Theme Demo Import', 'evolve' ) ); ?></h1>

	            <hr class="wp-header-end">
				<?php 
				//evolve_demo_preview();
				require plugin_dir_path( __FILE__ ) . '/admin-template.php';
				 ?>

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

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demo-awesome-admin.min.css', array(), $this->version, 'all' );
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

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demo-awesome-admin.min.js', array( 'jquery' ), $this->version, false );
		}
	}
}

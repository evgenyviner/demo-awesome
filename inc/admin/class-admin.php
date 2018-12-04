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

			// Add links to add post/page section
			add_action( 'admin_menu', array( $this, 'create_admin_menu_links' ) );
			add_action( 'edit_form_top', array( $this, 'edit_form_link' ) );

			// Include the front page builder
			add_action( 'init', array( $this, 'frontend_editor_view' ) );

			// Add the editor link to edit post/page list
			add_filter( 'page_row_actions', array( &$this, 'list_action_link' ) );
			add_filter( 'post_row_actions', array( &$this, 'list_action_link' ) );

			// Add custom content to frontend page
			add_action('wp_head', array($this, 'call_all_action_hook_for_frontend_view'));

			//remove all scripts/styles from wordpress
			add_action('demo_awesome_changing_before_wp_head', array($this, 'remove_all_default_enqueue_styles_and_scripts_from_wordpress'), 1);
			add_action('demo_awesome_changing_before_wp_head', array($this, 'call_enqueue_styles_and_scripts_from_wordpress'), 2);
			

		}
		
		function remove_all_default_enqueue_styles_and_scripts_from_wordpress(){
			//wp_dequeue_style('abc');

		}

		function call_enqueue_styles_and_scripts_from_wordpress(){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'fonts/styles.css', array(), $this->version, 'all' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
		}



		function call_all_action_hook_for_frontend_view(){
			if ( is_single() && ( $_REQUEST ) && isset( $_REQUEST['view_page'] ) && $_REQUEST['view_page'] == 'demo-awesome-view' ) {
				//add_button to add new item from frontend
				add_filter( 'the_content', array( $this, 'add_button_to_add_new_demo-awesome-importer' ) );
				add_shortcode( 'demo-awesome-view-add-item', array($this, 'demo_awesome_add_item_func') );
			}

		}
		function demo_awesome_add_item_func( $atts, $content = "" ) {
			ob_start();
			echo '<a href="#">' . __( 'Add Componentz Items', $this->plugin_name ) . '</a>';
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		// returns the content of $GLOBALS['post']
		// if the page is called 'debug'
		function add_button_to_add_new_demo_awesome($content) {
		  	return $content.'[demo-awesome-view-add-item]';
		}

		function list_action_link( $actions = array() ) {
			global $post;

			$preview_url_keys = array();

			if ( isset( $post->ID ) && is_numeric( $post->ID ) ) {
				// Set id of the page we are editing
				$preview_url_keys['post'] = $post->ID;

				// Set 'page' key – indicating that front page editing mode is active
				$front_editor = add_query_arg( array(
					'demo_awesome_id'   => $post->ID,
					'demo_awesome_type' => $post->post_type,
					'view_page'       => 'demo-awesome-importer.php',
				), admin_url() );

				$user_can = current_user_can( 'edit_post', $post->ID );
				if ( 'trash' != $post->post_status && $user_can && wp_check_post_lock( $post->ID ) === false ) {
					if ( true ) {
						$actions['demo-awesome-importer'] = '<a href="' . $front_editor . '">' . __( 'Edit with Componentz', $this->plugin_name ) . '</a>';
					}
				}
			}

			return $actions;
		}

		function frontend_editor_view() {
			if ( isset( $_REQUEST ) && isset( $_REQUEST['view_page'] ) && $_REQUEST['view_page'] == 'demo-awesome-importer.php' ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/frontend-display.php';
				die();
			}
		}

		function create_admin_menu_links() {
			add_submenu_page( 'edit.php', __( 'Add with Componentz', $this->plugin_name ), __( 'Add with Componentz', $this->plugin_name ), 'manage_options', $this->plugin_name, array(
				&$this,
				'admin_panel'
			) );

			add_submenu_page( 'edit.php?post_type=page', __( 'Add with Componentz', $this->plugin_name ), __( 'Add with Componentz', $this->plugin_name ), 'manage_options', $this->plugin_name, array(
				&$this,
				'admin_panel'
			) );

			$args = array(
				'public'   => true,
				'_builtin' => false
			);

			$output   = 'names'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'

			$post_types = get_post_types( $args, $output, $operator );

			foreach ( $post_types as $post_type ) {
				add_submenu_page( 'edit.php?post_type=' . $post_type, __( 'Add with Componentz', $this->plugin_name ), __( 'Add with Componentz', $this->plugin_name ), 'manage_options', $this->plugin_name, array(
					&$this,
					'admin_panel'
				) );
			}
		}

		function admin_panel() {
			// Create new post and redirect to frontend editor
			var_dump( $_REQUEST );
		}

		function edit_form_link( $post ) {
			if ( true || 'post' == $post->post_type ) {
				echo '<a target="_blank" href="' . add_query_arg( array(
						'demo_awesome_id'   => $post->ID,
						'demo_awesome_type' => $post->post_type,
						'view_page'       => 'demo-awesome-importer.php',
					), admin_url() ) . '" class="page-title-action" style="display: inline-block;float: left;margin: 0;margin-top: 10px;">' . __( "Edit with Componentz", $this->plugin_name ) . '</a>';
			}
		}

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

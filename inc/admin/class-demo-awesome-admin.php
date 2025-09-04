<?php
/**
 * The admin functionality of the plugin
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if (!defined('DEMO_AWESOME_IMPORTER_FOLDER')) {
    define('DEMO_AWESOME_IMPORTER_FOLDER', '/demo-awesome-import/');
}
if (!defined('DEMO_AWESOME_IMPORTER_SOURCE_URL')) {
    define('DEMO_AWESOME_IMPORTER_SOURCE_URL', esc_url('https://demo.theme4press.com/demo-import/'));
}

if (!class_exists('Demo_Awesome_Admin')) {
    class Demo_Awesome_Admin
    {

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
        public function __construct($plugin_name, $version)
        {

            $this->plugin_name = $plugin_name;
            $this->version = $version;

            add_action('admin_menu', array($this, 'importer_page'));
            add_action('load-plugins.php', array($this, 'admin_notice'));
            add_action('wp_ajax_call_import_function_from_ajax', array($this, 'call_import_function_from_ajax'));
            add_action('wp_ajax_required_plugins', array($this, 'required_plugins'));
            add_action('wp_ajax_evole_install_plugin', array($this, 'install_plugin'));
            add_action('wp_ajax_evole_activate_plugin', array($this, 'evole_activate_plugin'));
            add_action('demo_awesome_remove_old_posts_pages', array($this, 'remove_old_posts_pages'));
            add_action('wp_loaded', array($this, 'hide_notice'));
            add_filter(
                'customizer_demo_import_settings',
                array(
                    $this,
                    'update_customizer_data',
                ),
                10,
                2
            );
            add_filter('widget_demo_import_settings', array($this, 'update_widget_data'), 10, 4);

            if (isset($_GET['hide-notice']) && $_GET['hide-notice'] == 'demo_awesome_no_theme4press_theme_notice') {
                update_option('demo_awesome_no_theme4press_theme_notice', 0);
            }
            if (get_option('demo_awesome_no_theme4press_theme_notice', 1)) {
                add_action('admin_notices', array($this, 'no_theme4press_theme_notice'));
            }

        }

        /**
         * @since    1.0.0
         */
        public function admin_notice()
        {
            if (!get_option('demo_awesome_activation_notice')) {
                add_action('admin_notices', array($this, 'activation_notice'));
                update_option('demo_awesome_activation_notice', 0);
            } elseif (get_option('demo_awesome_activation_notice') == '1') {
                // Don't show any notice
            }
        }

        /**
         * @since    1.0.0
         */
        public static function hide_notice()
        {
            if (isset($_GET['demo-awesome-hide-notice']) && $_GET['demo-awesome-hide-notice'] == 'activation_notice') {
                if (!wp_verify_nonce($_GET['_demo_awesome_notice'], 'demo_awesome_hide_notice')) {
                    wp_die(__('Action failed. Please refresh the page and retry.', 'evolve'));
                }

                if (!current_user_can('manage_options')) {
                    wp_die(__('Cheatin&#8217; huh?', 'evolve'));
                }

                $hide_notice = sanitize_text_field($_GET['demo-awesome-hide-notice']);
                update_option('demo_awesome_'.$hide_notice, 1);
            }
        }

        /**
         * @since    1.0.0
         */
        function get_list_demos()
        {
            return $this->get_demo_packages(DEMO_AWESOME_IMPORTER_SOURCE_URL.'get-list-demos.json', 'get_list_demos');
        }

        /**
         * @since    1.0.0
         */
        function get_demo_packages($url, $template_name = '', $save_cache = true)
        {
            // Security: Validate URL
            if (!$this->validate_external_url($url)) {
                return false;
            }

            $packages = '';
            $decode_url = base64_encode($url);
            if (true || false === ($create_time = get_transient('demo_awesome_importer_packages_'.$decode_url))) {
                // Security: Use wp_safe_remote_get with proper args
                $raw_packages = wp_safe_remote_get($url, array(
                    'timeout' => 30,
                    'redirection' => 3,
                    'user-agent' => 'Demo Awesome Plugin',
                    'sslverify' => true,
                    'headers' => array(
                        'Accept' => 'application/json, text/plain, */*',
                    )
                ));
                
                if (!is_wp_error($raw_packages)) {
                    $response_code = wp_remote_retrieve_response_code($raw_packages);
                    if ($response_code === 200) {
                        $packages = wp_remote_retrieve_body($raw_packages);
                        
                        // Security: Validate response content
                        if ($packages && $this->validate_response_content($packages)) {
                            set_transient('demo_awesome_importer_packages_'.$decode_url, time(), HOUR_IN_SECONDS);
                            $this->write_file_to_local($packages, $decode_url.'.txt');
                        } else {
                            $packages = false;
                        }
                    }
                }
            } else {
                // Security: Use safe file reading
                $file_path = wp_upload_dir()['basedir'].DEMO_AWESOME_IMPORTER_FOLDER.$decode_url.'.txt';
                if (file_exists($file_path) && is_readable($file_path)) {
                    $packages = file_get_contents($file_path);
                    if (!$this->validate_response_content($packages)) {
                        $packages = false;
                    }
                }
            }

            return $packages;
        }

        /**
         * Validate external URL to prevent SSRF attacks
         * @since 1.0.3
         */
        private function validate_external_url($url) {
            // Check if URL is valid
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }
            
            $parsed_url = parse_url($url);
            
            // Check if URL has required components
            if (!isset($parsed_url['scheme']) || !isset($parsed_url['host'])) {
                return false;
            }
            
            // Only allow HTTPS
            if ($parsed_url['scheme'] !== 'https') {
                return false;
            }
            
            // Whitelist allowed hosts
            $allowed_hosts = ['demo.theme4press.com'];
            if (!in_array($parsed_url['host'], $allowed_hosts)) {
                return false;
            }
            
            // Check for localhost/private IP attempts
            $ip = gethostbyname($parsed_url['host']);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return false;
            }
            
            return true;
        }

        /**
         * Validate response content from external requests
         * @since 1.0.3
         */
        private function validate_response_content($content) {
            // Check for empty content
            if (empty($content)) {
                return false;
            }
            
            // Check content length (prevent DoS)
            if (strlen($content) > 10 * 1024 * 1024) { // 10MB limit for responses
                return false;
            }
            
            // Check for malicious content
            if (preg_match('/<\?php|<\?=|<script[^>]*>|javascript:|data:/i', $content)) {
                return false;
            }
            
            return true;
        }

        /**
         * @since    1.0.0
         */
        function get_import_file_content($template_name)
        {
            return $this->get_demo_packages(
                DEMO_AWESOME_IMPORTER_SOURCE_URL.$template_name."/content.xml",
                $template_name
            );
        }

        /**
         * @since    1.0.0
         */
        function get_import_file_path($template_name)
        {
            return $this->write_file_to_local($this->get_import_file_content($template_name));
        }

        /**
         * @since    1.0.0
         */
        function write_xml_file_to_local($file_content)
        {
            return $this->write_file_to_local($file_content);
        }

        /**
         * @since    1.0.0
         */
        function write_file_to_local($file_content, $file_name = 'content.xml')
        {
            // Security: Validate and sanitize filename
            $file_name = $this->validate_and_sanitize_filename($file_name);
            if (!$file_name) {
                return false;
            }

            // Security: Validate file content
            if (!$this->validate_file_content($file_content, $file_name)) {
                return false;
            }

            global $wp_filesystem;
            // Initialize the WP filesystem, no more using 'file-put-contents' function
            if (empty($wp_filesystem)) {
                require_once wp_normalize_path(ABSPATH.'/wp-admin/includes/file.php');
                WP_Filesystem();
            }
            
            $upload_dir = wp_upload_dir()['basedir'].DEMO_AWESOME_IMPORTER_FOLDER;
            if (!is_dir($upload_dir)) {
                wp_mkdir_p($upload_dir);
                // Set proper permissions
                chmod($upload_dir, 0755);
            }
            
            $file_path = $upload_dir.$file_name;
            
            // Security: Additional path validation
            $real_upload_dir = realpath($upload_dir);
            $real_file_path = realpath(dirname($file_path));
            if ($real_file_path === false || strpos($real_file_path, $real_upload_dir) !== 0) {
                return false;
            }
            
            $result = $wp_filesystem->put_contents(
                $file_path,
                $file_content,
                FS_CHMOD_FILE // predefined mode settings for WP files
            );

            return $result ? $file_path : false;
        }

        /**
         * Validate and sanitize filename to prevent path traversal and malicious files
         * @since 1.0.3
         */
        private function validate_and_sanitize_filename($filename) {
            // Remove any path components
            $filename = basename($filename);
            
            // Check for empty filename
            if (empty($filename)) {
                return false;
            }
            
            // Check for path traversal attempts
            if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
                return false;
            }
            
            // Check for null bytes
            if (strpos($filename, "\0") !== false) {
                return false;
            }
            
            // Sanitize filename
            $filename = sanitize_file_name($filename);
            
            // Validate file extension
            $allowed_extensions = array('xml', 'dat', 'wie', 'zip', 'txt', 'json');
            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_extensions)) {
                return false;
            }
            
            // Check filename length
            if (strlen($filename) > 255) {
                return false;
            }
            
            return $filename;
        }

        /**
         * Validate file content to prevent malicious uploads
         * @since 1.0.3
         */
        private function validate_file_content($content, $filename) {
            // Check for empty content
            if (empty($content)) {
                return false;
            }
            
            // Check content length (prevent DoS)
            if (strlen($content) > 50 * 1024 * 1024) { // 50MB limit
                return false;
            }
            
            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            // Content validation based on file type
            switch ($file_extension) {
                case 'xml':
                    return $this->validate_xml_content($content);
                case 'json':
                    return $this->validate_json_content($content);
                case 'dat':
                    return $this->validate_dat_content($content);
                case 'wie':
                    return $this->validate_wie_content($content);
                case 'zip':
                    return $this->validate_zip_content($content);
                case 'txt':
                    return $this->validate_txt_content($content);
                default:
                    return false;
            }
        }

        /**
         * Validate XML content
         * @since 1.0.3
         */
        private function validate_xml_content($content) {
            // Check for PHP code injection
            if (preg_match('/<\?php|<\?=|<script/i', $content)) {
                return false;
            }
            
            // Basic XML structure validation
            $dom = new DOMDocument();
            $dom->loadXML($content, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTR);
            
            if ($dom === false) {
                return false;
            }
            
            return true;
        }

        /**
         * Validate JSON content
         * @since 1.0.3
         */
        private function validate_json_content($content) {
            // Check for PHP code injection
            if (preg_match('/<\?php|<\?=|<script/i', $content)) {
                return false;
            }
            
            $decoded = json_decode($content, true);
            return json_last_error() === JSON_ERROR_NONE;
        }

        /**
         * Validate DAT content (serialized data)
         * @since 1.0.3
         */
        private function validate_dat_content($content) {
            // Check for PHP code injection
            if (preg_match('/<\?php|<\?=|<script/i', $content)) {
                return false;
            }
            
            // Basic validation for serialized data
            $unserialized = maybe_unserialize($content);
            return $unserialized !== false;
        }

        /**
         * Validate WIE content (widget import/export)
         * @since 1.0.3
         */
        private function validate_wie_content($content) {
            // Check for PHP code injection
            if (preg_match('/<\?php|<\?=|<script/i', $content)) {
                return false;
            }
            
            $decoded = json_decode($content, true);
            return json_last_error() === JSON_ERROR_NONE;
        }

        /**
         * Validate ZIP content
         * @since 1.0.3
         */
        private function validate_zip_content($content) {
            // Check ZIP file signature
            if (substr($content, 0, 2) !== 'PK') {
                return false;
            }
            
            // Additional ZIP validation could be added here
            return true;
        }

        /**
         * Validate TXT content
         * @since 1.0.3
         */
        private function validate_txt_content($content) {
            // Check for PHP code injection
            if (preg_match('/<\?php|<\?=|<script/i', $content)) {
                return false;
            }
            
            return true;
        }

        /**
         * @since    1.0.0
         */
        function remove_old_posts_pages()
        {
            $list_posts_pages = get_posts(
                array(
                    'posts_per_page' => -1,
                    'post_type' => array('post', 'page', 'product', 'slide', 'evolve_portfolio'),
                )
            );
            if ($list_posts_pages) {
                foreach ($list_posts_pages as $post_item) {
                    wp_delete_post($post_item->ID, true);
                }
            }
        }

        function sanitize_nested_array($array)
        {
            $sanitized_array = array();
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $sanitized_array[$key] = $this->sanitize_nested_array($value);
                } else {
                    $sanitized_array[$key] = sanitize_text_field($value);
                }
            }
            return $sanitized_array;
        }

        /**
         * Enhanced validation and sanitization for demo data
         * @since 1.0.3
         */
        private function validate_and_sanitize_demo_data($data) {
            if (!is_array($data)) {
                return false;
            }

            $allowed_keys = array(
                'folder_path', 'name', 'premium_demo', 'has_theme4press_slider_data',
                'has_slider_revolution_data', 'has_layerslider_data', 'plugins',
                'option_update', 'update_galleries', 'customizer_data_update',
                'widgets_data_update', 'require_ver_premium', 'require_ver_free'
            );

            $sanitized_data = array();
            
            foreach ($data as $key => $value) {
                // Only allow whitelisted keys
                if (!in_array($key, $allowed_keys)) {
                    continue;
                }

                if (is_array($value)) {
                    $sanitized_data[$key] = $this->sanitize_nested_array($value);
                } else {
                    $sanitized_data[$key] = sanitize_text_field($value);
                }
            }

            return $sanitized_data;
        }

        /**
         * Validate template name to prevent directory traversal
         * @since 1.0.3
         */
        private function validate_template_name($template_name) {
            if (empty($template_name)) {
                return false;
            }

            // Check for path traversal attempts
            if (strpos($template_name, '..') !== false || 
                strpos($template_name, '/') !== false || 
                strpos($template_name, '\\') !== false) {
                return false;
            }

            // Check for null bytes
            if (strpos($template_name, "\0") !== false) {
                return false;
            }

            // Sanitize and validate
            $template_name = sanitize_file_name($template_name);
            
            // Check length
            if (strlen($template_name) > 100) {
                return false;
            }

            // Only allow alphanumeric, hyphens, and underscores
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $template_name)) {
                return false;
            }

            return true;
        }

        /**
         * Rate limiting to prevent abuse
         * @since 1.0.3
         */
        private function check_rate_limit() {
            $user_id = get_current_user_id();
            $transient_key = 'demo_awesome_rate_limit_' . $user_id;
            
            $requests = get_transient($transient_key);
            if ($requests === false) {
                $requests = 0;
            }

            // Allow 5 requests per hour
            if ($requests >= 5) {
                return false;
            }

            set_transient($transient_key, $requests + 1, HOUR_IN_SECONDS);
            return true;
        }

        /**
         * Validate plugin name to prevent malicious plugin installation
         * @since 1.0.3
         */
        private function validate_plugin_name($plugin_name) {
            if (empty($plugin_name)) {
                return false;
            }

            // Check for path traversal attempts
            if (strpos($plugin_name, '..') !== false || 
                strpos($plugin_name, '/') !== false || 
                strpos($plugin_name, '\\') !== false) {
                return false;
            }

            // Check for null bytes
            if (strpos($plugin_name, "\0") !== false) {
                return false;
            }

            // Check length
            if (strlen($plugin_name) > 100) {
                return false;
            }

            // Only allow alphanumeric, hyphens, underscores, and spaces
            if (!preg_match('/^[a-zA-Z0-9_\- ]+$/', $plugin_name)) {
                return false;
            }

            // Whitelist of allowed plugin names (add more as needed)
            $allowed_plugins = array(
                'LayerSlider WP', 'LayerSlider', 'Slider Revolution', 'revslider',
                'Contact Form 7', 'WooCommerce', 'Elementor', 'Beaver Builder'
            );

            return in_array($plugin_name, $allowed_plugins);
        }

        /**
         * @since    1.0.0
         */
        function call_import_function_from_ajax()
        {
            // Security: Enhanced nonce verification
            if ( !wp_verify_nonce( $_POST['_nonce'] ?? '', 'demo-awesome' ) ) {
                wp_send_json_error( array(
                    'message' => 'Security check failed. Please refresh the page and try again.',
                    'code' => 'invalid_nonce'
                ));
                wp_die();
            }

            // Security: Enhanced capability check
            if ( !current_user_can('manage_options') ) {
                wp_send_json_error( array(
                    'message' => 'You do not have sufficient permissions to access this feature.',
                    'code' => 'insufficient_permissions'
                ));
                wp_die();
            }

            // Security: Rate limiting check
            if (!$this->check_rate_limit()) {
                wp_send_json_error( array(
                    'message' => 'Too many requests. Please wait before trying again.',
                    'code' => 'rate_limit_exceeded'
                ));
                wp_die();
            }
            
            $data_demo_raw = isset($_REQUEST['data_demo']) ? $_REQUEST['data_demo'] : array();
            $data_demo = array();

            // Security: Enhanced input validation and sanitization
            if (is_array($data_demo_raw)) {
                $data_demo = $this->validate_and_sanitize_demo_data($data_demo_raw);
                if ($data_demo === false) {
                    wp_send_json_error( array(
                        'message' => 'Invalid demo data provided.',
                        'code' => 'invalid_data'
                    ));
                    wp_die();
                }
            } else {
                $data_demo = sanitize_text_field($data_demo_raw);
            }

            $template_name = isset($data_demo['folder_path']) ? sanitize_text_field($data_demo['folder_path']) : '';

            // Security: Validate template name
            if (!$this->validate_template_name($template_name)) {
                wp_send_json_error( array(
                    'message' => 'Invalid template name.',
                    'code' => 'invalid_template'
                ));
                wp_die();
            }

            $this->remove_old_datas($data_demo, $template_name);

            if (Demo_Awesome_Admin::is_premium_theme() == false && $data_demo['premium_demo']) {
                wp_send_json_success(
                    array(
                        'success' => true,
                        'message' => sprintf(
                            '<span>%s</span>',
                            esc_html__('The premium demo requires the premium theme version', 'demo-awesome')
                        ),
                    )
                );
                wp_die();
            }
            // Import content data
            $this->import_content_theme($data_demo, $template_name);
            // Import customizer
            $this->import_customizer_data($data_demo, $template_name);
            // Fix menu
            $this->update_nav_menu_items($data_demo, $template_name);
            // Fix option
            $this->update_option_data($data_demo, $template_name);
            // Import widget
            $this->import_widget_settings($data_demo, $template_name);
            // Import Theme4Press slider
            $this->import_theme4press_slider($data_demo, $template_name);
            // Import Slider Revolution slider
            $this->import_slider_revolution($data_demo, $template_name);
            // Import LayerSlider slider
            $this->import_layerslider($data_demo, $template_name);
            // Fix galleries data
            $this->update_galleries_data($data_demo, $template_name);

            wp_send_json_success(
                array(
                    'success' => true,
                    'message' => sprintf(
                        '<div>%1$s<span class="mr-1">%2$s</span>%3$s%4$s%5$s</div>',
                        '<h3>',
                        Demo_Awesome_Admin::get_svg('check'),
                        esc_html__('Import finished', 'demo-awesome'),
                        '</h3>',
                        esc_html__('The demo has been imported successfully', 'demo-awesome')
                    ),
                )
            );

            wp_die(); // this is required to terminate immediately and return a proper response
        }

        /**
         * @since    1.0.0
         */
        function remove_old_datas($data_demo, $template_name)
        {

            do_action('demo_awesome_begin_remove_old_datas');

            delete_option('theme_mods_evolve-plus');
            delete_option('theme_mods_evolve');

            // remove all of old nav menus
            $list_menus = get_posts(
                array(
                    'post_type' => 'nav_menu_item',
                    'posts_per_page' => -1,
                )
            );
            if ($list_menus) {
                foreach ($list_menus as $menu) {
                    wp_delete_post($menu->ID, true);
                }
            }

            // remove all widgets data
            $list_widgets = wp_load_alloptions();
            if ($list_widgets) {
                delete_option('sidebars_widgets');
                foreach ($list_widgets as $option => $value) {
                    if (strpos($option, 'widget_') === 0) {
                        delete_option($option);
                    }
                }
            }
            do_action('demo_awesome_remove_old_posts_pages');

            // fix data for premium theme version need to import free data demo version
            if (Demo_Awesome_Admin::is_premium_theme() == true && !$data_demo['premium_demo']) {
                update_option('check_updated_to_new_bootstrap_slider_data_', false);
                update_option('check_updated_to_new_parallax_slider_data_', false);
                update_option('check_updated_to_new_content_boxes_data_', false);
                update_option('check_updated_to_new_testimonials_data_', false);
                update_option('check_updated_to_new_counter_circle_data_', false);
            }

        }

        /**
         * @since    1.0.0
         */
        function required_plugins()
        {
            // Security: Enhanced nonce verification
            if (!wp_verify_nonce($_POST['_nonce'] ?? '', 'demo-awesome')) {
                wp_die(__('Security check failed.', 'demo-awesome'));
            }
            
            // Security: Enhanced capability check
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions.', 'demo-awesome'));
            }

            // Security: Rate limiting check
            if (!$this->check_rate_limit()) {
                wp_die(__('Too many requests. Please wait before trying again.', 'demo-awesome'));
            }
            
            // Include the required plugins list
            require dirname(__FILE__).'/required-plugins.php';
            $data_demo_raw = isset($_REQUEST['data_demo']) ? $_REQUEST['data_demo'] : array();
            $data_demo = array();

            // Security: Enhanced input validation and sanitization
            if (is_array($data_demo_raw)) {
                $data_demo = $this->validate_and_sanitize_demo_data($data_demo_raw);
                if ($data_demo === false) {
                    wp_die(__('Invalid demo data provided.', 'demo-awesome'));
                }
            } else {
                $data_demo = sanitize_text_field($data_demo_raw);
            }

            demo_awesome_required_plugins($data_demo);
            wp_die(); // this is required to terminate immediately and return a proper response
        }

        function evole_activate_plugin()
        {
            // Security: Enhanced nonce verification
            if (!wp_verify_nonce($_POST['_nonce'] ?? '', 'demo-awesome')) {
                wp_die(__('Security check failed.', 'demo-awesome'));
            }
    
            // Security: Enhanced capability check
            if (!current_user_can('activate_plugins')) {
                wp_die(__('Sorry, you are not allowed to activate plugins on this site.', 'demo-awesome'));
            }

            // Security: Rate limiting check
            if (!$this->check_rate_limit()) {
                wp_die(__('Too many requests. Please wait before trying again.', 'demo-awesome'));
            }
            
            // Security: Validate and sanitize plugin name
            $plugin = sanitize_text_field($_POST['plugin']);
            if (!$this->validate_plugin_name($plugin)) {
                wp_die(__('Invalid plugin name provided.', 'demo-awesome'));
            }
            
            $activate = isset($_POST['activate']) ? true : false;

            if ($activate == false) {
                if ($plugin == 'LayerSlider WP') {
                    evolve_get_update_plugin('layerslider');
                    $plugin = 'LayerSlider';
                } elseif ($plugin == 'slider-revolution') {
                    $plugin = 'revslider';
                } else {
                    evolve_get_update_plugin($plugin);

                }

            }
            $html = str_replace('Not installed', 'Not active', stripslashes($_POST['html']));
            $all_plugins = apply_filters('all_plugins', get_plugins());
            foreach ($all_plugins as $k => $item) {

                if (preg_match('#'.$plugin.'#', $k)) {
                    activate_plugin($k);
                }
            }

            echo  wp_kses_post($html);

            die();

        }

        function install_plugin()
        {   
            // Security: Enhanced nonce verification
            if (!wp_verify_nonce($_POST['_nonce'] ?? '', 'demo-awesome')) {
                wp_die(__('Security check failed.', 'demo-awesome'));
            }

            // Security: Enhanced capability check
            if (!current_user_can('install_plugins')) {
                wp_die(__('Sorry, you are not allowed to install plugins on this site.', 'demo-awesome'));
            }

            // Security: Rate limiting check
            if (!$this->check_rate_limit()) {
                wp_die(__('Too many requests. Please wait before trying again.', 'demo-awesome'));
            }

            // Security: Validate and sanitize plugin name
            $plugin = sanitize_text_field($_POST['plugin']);
            if (!$this->validate_plugin_name($plugin)) {
                wp_die(__('Invalid plugin name provided.', 'demo-awesome'));
            }
            
            $activate = isset($_POST['activate']) ? true : false;

            if ($activate == false) {
                if ($plugin == 'LayerSlider WP') {
                    evolve_get_update_plugin('layerslider');
                    $plugin = 'LayerSlider';
                } elseif ($plugin == 'slider-revolution') {
                    $plugin = 'revslider';
                } else {
                    evolve_get_update_plugin($plugin);

                }

            }

            $all_plugins = apply_filters('all_plugins', get_plugins());

            foreach ($all_plugins as $k => $item) {

                if (preg_match('#'.$plugin.'#', $k)) {
                    activate_plugin($k);
                }
            }
            $html = str_replace('Not installed', 'Not active', stripslashes($_POST['html']));

            echo wp_kses_post($html);


            wp_die(); // this is required to terminate immediately and return a proper response

        }

        /**
         * @since    1.0.0
         */
        function get_import_file_path_from_live_demo($template_name, $file_name)
        {
            return $this->write_file_to_local(
                $this->get_demo_packages(DEMO_AWESOME_IMPORTER_SOURCE_URL.$template_name.'/'.$file_name),
                $file_name
            );
        }

        /**
         * @since    1.0.0
         */
        function update_option_data($data_demo, $template_name = 'blog')
        {
            if (!empty($data_demo['option_update'])) {
                foreach ($data_demo['option_update'] as $data_type => $data_value) {
                    if ($data_type == 'update_pages' && $data_value && is_array($data_value) && count($data_value)) {
                        foreach ($data_value as $option_name => $option_value) {
                            $page = get_page_by_title($option_value);
                            if (is_object($page) && $page->ID) {
                                update_option($option_name, $page->ID);
                            }
                        }
                    } else {
                        update_option($data_type, $data_value);
                    }
                }
            }
        }

        /**
         * @since    1.0.0
         */
        function update_galleries_data($data_demo, $template_name = 'blog')
        {
            if (!empty($data_demo['update_galleries']) && !empty($data_demo['update_galleries']['pages'])) {
                foreach ($data_demo['update_galleries']['pages'] as $data_value) {
                    if (!empty($data_value['title'])) {
                        $page = get_page_by_title($data_value['title']);
                        if (is_object($page) && $page->ID) {
                            foreach ($data_value['items'] as $shortcode_values) {
                                $names = explode(',', $shortcode_values['names']);
                                $ids = explode(',', $shortcode_values['ids']);
                                $new_ids = array();
                                foreach ($ids as $id_key => $id) {
                                    $attach = get_page_by_title($names[$id_key], OBJECT, 'attachment');
                                    if (is_object($attach) && $attach->ID) {
                                        $new_ids[] = $attach->ID;
                                    }
                                }
                                if ($new_ids) {
                                    $new_ids_string = implode(',', $new_ids);
                                    $post_content_new = str_replace(
                                        'ids="'.$shortcode_values['ids'],
                                        'ids="'.$new_ids_string,
                                        $page->post_content
                                    );
                                    $my_post = array(
                                        'ID' => $page->ID,
                                        'post_content' => $post_content_new,
                                    );
                                    // Update the post into the database
                                    wp_update_post($my_post);
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
        public function import_content_theme($data_demo, $template_name = 'blog')
        {

            $import_file = $this->get_import_file_path($template_name);

            // Load Importer API.
            require_once ABSPATH.'wp-admin/includes/import.php';

            if (!class_exists('WP_Importer')) {
                $class_wp_importer = ABSPATH.'wp-admin/includes/class-wp-importer.php';

                if (file_exists($class_wp_importer)) {
                    require $class_wp_importer;
                }
            }

            // Include Class Demo Awesome Importer
            require dirname(__FILE__).'/importer/class-demo-awesome-importer.php';

            if (is_file($import_file)) {
                $wp_import = new Demo_Awesome_Importer();
                $wp_import->fetch_attachments = true;

                ob_start();
                $wp_import->import($import_file);
                ob_end_clean();

                flush_rewrite_rules();
            } else {
                $status['errorMessage'] = esc_html__('The content data file (XML) is missing.', 'demo-awesome');
                wp_send_json_error($status);
            }

            return true;
        }

        /**
         * @since    1.0.0
         */
        function import_theme4press_slider($data_demo, $template_name = 'blog')
        {

            if ($data_demo['has_theme4press_slider_data']) {
                if (class_exists('Theme4Press_Slider')) {
                    $import_file = $this->get_import_file_path_from_live_demo($template_name, 'theme4press_slider.zip');

                    if (is_file($import_file)) {
                        $theme4press_slider = new Theme4Press_Slider();
                        $theme4press_slider->import_sliders($import_file);
                    } else {
                        $status['errorMessage'] = esc_html__(
                            'The Theme4Press Slider data file (ZIP) is missing.',
                            'demo-awesome'
                        );
                        wp_send_json_error($status);
                    }
                }
            }

            return true;
        }

        /**
         * @since    1.0.0
         */
        function import_slider_revolution($data_demo, $template_name = 'blog')
        {

            if ($data_demo['has_slider_revolution_data']) {
                if (Demo_Awesome_Admin::is_plugin_activated('Slider Revolution')) {
                    $import_file = $this->get_import_file_path_from_live_demo($template_name, 'slider_revolution.zip');

                    if (is_file($import_file)) {

                        $_FILES["import_file"]["tmp_name"] = $import_file;
                        $slider = new RevSlider();
                        $results = $slider->importSliderFromPost();

                        if (is_wp_error($results)) {
                            return false;
                        }
                    } else {
                        $status['errorMessage'] = esc_html__(
                            'The Slider Revolution data file (ZIP) is missing.',
                            'demo-awesome'
                        );
                        wp_send_json_error($status);
                    }
                }
            }

            return true;
        }

        /**
         * @since    1.0.0
         */
        function import_layerslider($data_demo, $template_name = 'blog')
        {

            if ($data_demo['has_layerslider_data']) {
                if (Demo_Awesome_Admin::is_plugin_activated('LayerSlider WP')) {
                    $import_file = $this->get_import_file_path_from_live_demo($template_name, 'layerslider.zip');

                    if (is_file($import_file)) {
                        include_once LS_ROOT_PATH.'/classes/class.ls.importutil.php';
                        $results = new LS_ImportUtil($import_file, 'layerslider.zip');

                        if (is_wp_error($results)) {
                            return false;
                        }
                    } else {
                        $status['errorMessage'] = esc_html__(
                            'The LayerSlider data file (ZIP) is missing.',
                            'demo-awesome'
                        );
                        wp_send_json_error($status);
                    }
                }
            }

            return true;
        }

        /**
         * @since    1.0.0
         */
        function import_widget_settings($data_demo, $template_name = 'blog')
        {

            require dirname(__FILE__).'/importer/class-demo-awesome-widget-importer.php';

            $import_file = $this->get_import_file_path_from_live_demo($template_name, 'widgets.wie');

            if (is_file($import_file)) {
                $results = Demo_Awesome_Widget_Importer::import($import_file, $data_demo);

                if (is_wp_error($results)) {
                    return false;
                }
            } else {
                $status['errorMessage'] = esc_html__('The widget data file (WIE) is missing.', 'demo-awesome');
                wp_send_json_error($status);
            }

            return true;
        }

        /**
         * @since    1.0.0
         */
        function import_customizer_data($data_demo, $template_name = 'blog')
        {
            require dirname(__FILE__).'/importer/class-demo-awesome-customizer-importer.php';

            $import_file = $this->get_import_file_path_from_live_demo($template_name, 'customizer.dat');

            if (is_file($import_file)) {
                $results = Demo_Awesome_Customizer_Importer::import($import_file, $data_demo);

                if (is_wp_error($results)) {
                    return false;
                }
            } else {
                $status['errorMessage'] = esc_html__('The customizer data file (DAT) is missing.', 'demo-awesome');
                wp_send_json_error($status);
            }

            return true;
        }

        /**
         * @since    1.0.0
         */
        function update_nav_menu_items()
        {
            $menu_locations = get_nav_menu_locations();

            foreach ($menu_locations as $location => $menu_id) {

                if (is_nav_menu($menu_id)) {
                    $menu_items = wp_get_nav_menu_items($menu_id, array('post_status' => 'any'));

                    if (!empty($menu_items)) {
                        foreach ($menu_items as $menu_item) {
                            if (isset($menu_item->url) && isset($menu_item->db_id) && 'custom' == $menu_item->type) {
                                $site_parts = parse_url(home_url('/'));
                                $menu_parts = parse_url($menu_item->url);

                                // Update existing custom nav menu item URL.
                                if (isset($menu_parts['path']) && isset($menu_parts['host']) && apply_filters(
                                        'demo_awesome_importer_nav_menu_item_url_hosts',
                                        in_array($menu_parts['host'], array('demo.theme4press.com'))
                                    )) {
                                    $menu_item->url = str_replace(
                                        array(
                                            $menu_parts['scheme'],
                                            $menu_parts['host'],
                                            $menu_parts['path'],
                                        ),
                                        array(
                                            $site_parts['scheme'],
                                            $site_parts['host'],
                                            trailingslashit($site_parts['path']),
                                        ),
                                        $menu_item->url
                                    );
                                    update_post_meta($menu_item->db_id, '_menu_item_url', esc_url_raw($menu_item->url));
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
        function update_customizer_data($data, $demo_data = array())
        {
            if (empty($demo_data['customizer_data_update'])) {
                $demo_data['customizer_data_update']['nav_menu_locations'] = array(
                    'primary-menu' => 'Main menu',
                    'sticky_navigation' => 'Main menu',
                );
            }
            if (!empty($demo_data['customizer_data_update'])) {
                foreach ($demo_data['customizer_data_update'] as $data_type => $data_value) {
                    if (!in_array($data_type, array('pages', 'categories', 'nav_menu_locations'))) {
                        continue;
                    }

                    // Format the value based on data type.
                    switch ($data_type) {
                        case 'pages':
                            foreach ($data_value as $option_key => $option_value) {
                                if (!empty($data['mods'][$option_key])) {
                                    $page = get_page_by_title($option_value);

                                    if (is_object($page) && $page->ID) {
                                        $data['mods'][$option_key] = $page->ID;
                                    }
                                }
                            }
                            break;
                        case 'categories':
                            foreach ($data_value as $taxonomy => $taxonomy_data) {
                                if (!taxonomy_exists($taxonomy)) {
                                    continue;
                                }

                                foreach ($taxonomy_data as $option_key => $option_value) {
                                    if (!empty($data['mods'][$option_key])) {
                                        $term = get_term_by('name', $option_value, $taxonomy);

                                        if (is_object($term) && $term->term_id) {
                                            $data['mods'][$option_key] = $term->term_id;
                                        }
                                    }
                                }
                            }
                            break;
                        case 'nav_menu_locations':
                            $nav_menus = wp_get_nav_menus();

                            if (!empty($nav_menus)) {
                                foreach ($nav_menus as $nav_menu) {
                                    if (is_object($nav_menu)) {
                                        foreach ($data_value as $location => $location_name) {
                                            if ($nav_menu->name == $location_name) {
                                                $data['mods'][$data_type][$location] = $nav_menu->term_id;
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
        function update_widget_data($widget, $widget_type, $instance_id, $demo_data)
        {
            if ('nav_menu' == $widget_type) {
                $nav_menu = wp_get_nav_menu_object($widget['title']);

                if (is_object($nav_menu) && $nav_menu->term_id) {
                    $widget['nav_menu'] = $nav_menu->term_id;
                }
            } elseif (!empty($demo_data['widgets_data_update'])) {
                foreach ($demo_data['widgets_data_update'] as $dropdown_type => $dropdown_data) {
                    if (!in_array($dropdown_type, array('dropdown_pages', 'dropdown_categories'))) {
                        continue;
                    }

                    // Format the value based on dropdown type.
                    switch ($dropdown_type) {
                        case 'dropdown_pages':
                            foreach ($dropdown_data as $widget_id => $widget_data) {
                                if (!empty($widget_data[$instance_id]) && $widget_id == $widget_type) {
                                    foreach ($widget_data[$instance_id] as $widget_key => $widget_value) {
                                        $page = get_page_by_title($widget_value);

                                        if (is_object($page) && $page->ID) {
                                            $widget[$widget_key] = $page->ID;
                                        }
                                    }
                                }
                            }
                            break;
                        case 'dropdown_categories':
                            foreach ($dropdown_data as $taxonomy => $taxonomy_data) {
                                if (!taxonomy_exists($taxonomy)) {
                                    continue;
                                }

                                foreach ($taxonomy_data as $widget_id => $widget_data) {
                                    if (!empty($widget_data[$instance_id]) && $widget_id == $widget_type) {
                                        foreach ($widget_data[$instance_id] as $widget_key => $widget_value) {
                                            $term = get_term_by('name', $widget_value, $taxonomy);

                                            if (is_object($term) && $term->term_id) {
                                                $widget[$widget_key] = $term->term_id;
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
        public static function is_theme4press_theme()
        {
            $demo_awesome_my_theme = wp_get_theme();
            if ($demo_awesome_my_theme->get('Name') == 'evolve' || $demo_awesome_my_theme->get(
                    'Name'
                ) == 'evolve Child' || $demo_awesome_my_theme->get(
                    'Name'
                ) == 'evolve Plus' || $demo_awesome_my_theme->get('Name') == 'evolve Plus Child') {
                return true;
            }

            return false;
        }

        /**
         * @since    1.0.0
         */
        public function activation_notice()
        {
            if (!Demo_Awesome_Admin::is_theme4press_theme()) {
                return;
            }

            wp_enqueue_style('demo-awesome-notice', plugin_dir_url(__FILE__).'css/notice.css'); ?>

            <div class="notice demo-awesome-notice is-dismissible">
                <p>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__)); ?>images/logo.png"/><?php echo sprintf(
                        esc_html__(
                            'Thank you for installing %1$sDemo Awesome%2$s plugin by Theme4Press. To start importing a demo content please visit the importer page',
                            'evolve'
                        ),
                        '<strong>',
                        '</strong>'
                    ); ?>
                    <a class="button"
                       href="<?php echo esc_url(
                           admin_url('themes.php?page=demo-awesome-importer')
                       ); ?>"><?php esc_html_e('Let\'s Get Started', 'demo-awesome'); ?></a>
                    <a href="<?php echo esc_url(
                        wp_nonce_url(
                            remove_query_arg(
                                array('activated'),
                                add_query_arg('demo-awesome-hide-notice', 'activation_notice')
                            ),
                            'demo_awesome_hide_notice',
                            '_demo_awesome_notice'
                        )
                    ); ?>"><?php esc_html_e('Dismiss', 'demo-awesome'); ?></a>
                </p>
            </div>
            <?php
        }

        /**
         * @since    1.0.0
         */
        public function no_theme4press_theme_notice()
        {
            if (Demo_Awesome_Admin::is_theme4press_theme()) {
                return;
            }

            wp_enqueue_style('demo-awesome-notice', plugin_dir_url(__FILE__).'css/notice.css'); ?>

            <div class="notice demo-awesome-notice is-dismissible">
                <p><?php echo wp_kses_post( Demo_Awesome_Admin::is_theme4press_theme_message()); ?><a
                            href="<?php echo esc_url(
                                add_query_arg('hide-notice', 'demo_awesome_no_theme4press_theme_notice')
                            ); ?>"><?php esc_html_e('Dismiss', 'demo-awesome'); ?></a>
                </p>
            </div>
            <?php
        }

        /**
         * @since    1.0.0
         */
        public static function is_theme4press_theme_message()
        {
            $message = '';
            if (!Demo_Awesome_Admin::is_theme4press_theme()) {
                $message = "<span><img src='".plugin_dir_url(__FILE__)."images/logo.png' />".sprintf(
                        esc_html__(
                            'The %1$sDemo Awesome%2$s plugin is designed only for %3$sTheme4Press%4$s themes',
                            'demo-awesome'
                        ),
                        '<strong>',
                        '</strong>',
                        '<strong>',
                        '</strong>'
                    )."</span><a class='button button-primary' target='_blank' href='".get_admin_url(
                           )."theme-install.php?search=theme4press"."'>".esc_html__(
                               'Install theme',
                               'demo-awesome'
                           )."</a>";
            }

            return $message;
        }

        /**
         * @since    1.0.0
         */
        public static function is_free_theme()
        {
            $demo_awesome_my_theme = wp_get_theme();
            if ($demo_awesome_my_theme->get('Name') == 'evolve' || $demo_awesome_my_theme->get(
                    'Name'
                ) == 'evolve Child') {
                return true;
            }

            return false;
        }

        /**
         * @since    1.0.0
         */
        public static function is_premium_theme()
        {
            $demo_awesome_my_theme = wp_get_theme();
            if ($demo_awesome_my_theme->get('Name') == 'evolve Plus' || $demo_awesome_my_theme->get(
                    'Name'
                ) == 'evolve Plus Child') {
                return true;
            }

            return false;
        }

        /**
         * @since    1.0.0
         */
        public static function get_svg($icon = null)
        {

            if (empty($icon)) {
                return '';
            }

            $icon = esc_attr($icon);

            // Constructing the SVG element
            $svg = '<svg class="demo-awesome-icon-' . $icon . '" aria-hidden="true" role="img">';
            $svg .= '<use xlink:href="' . esc_url(plugin_dir_url(__FILE__) . '/images/icons.svg#demo-awesome-icon-' . $icon) . '"></use>';
            $svg .= '</svg>';

            return $svg;
        }

        /**
         * @since    1.0.0
         */
        public static function is_plugin_installed($plugin)
        {

            if (!function_exists('get_plugins')) {
                require_once ABSPATH.'wp-admin/includes/plugin.php';
            }

            $all_plugins = get_plugins();

            foreach ($all_plugins as $single_plugin) {
                if (isset($single_plugin['Name']) && $single_plugin['Name'] == $plugin) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @since    1.0.0
         */
        public static function is_plugin_activated($plugin)
        {

            if (!function_exists('get_plugins')) {
                require_once ABSPATH.'wp-admin/includes/plugin.php';
            }

            $all_plugins = get_plugins();
            $active_plugins = get_option('active_plugins');

            foreach ($active_plugins as $item) {
                if (isset($all_plugins[$item]['Name']) && $all_plugins[$item]['Name'] == $plugin) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @since    1.0.0
         */
        function importer_page()
        {
            if (!current_user_can('manage_options')) {
                return;
            }
            add_theme_page(
                esc_html__('Demo Awesome - The Data Importer', 'demo-awesome'),
                esc_html__('Demo Awesome', 'demo-awesome'),
                'edit_theme_options',
                'demo-awesome-importer',
                array(
                    $this,
                    'demo_browser',
                )
            );
        }

        /**
         * @since    1.0.0
         */
        function demo_browser()
        { ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php echo esc_html__(
                        'Demo Awesome - The Data Importer',
                        'demo-awesome'
                    ); ?></h1>

                <hr class="wp-header-end">
                <?php
                $demo_awesome_get_list_demos = $this->get_list_demos();
                require plugin_dir_path(__FILE__).'/demo-browser.php';
                ?>
            </div>

        <?php }

        /**
         * @since    1.0.0
         */
        public function enqueue_styles($hook)
        {

            if ('appearance_page_demo-awesome-importer' != $hook) {
                return;
            }

            wp_enqueue_style('demo-awesome', plugin_dir_url(__FILE__).'css/admin.css');
        }

        /**
         * @since    1.0.0
         */
        public function enqueue_scripts($hook)
        {

            if ('appearance_page_demo-awesome-importer' != $hook) {
                return;
            }

            wp_enqueue_script('demo-awesome', plugin_dir_url(__FILE__).'js/admin.js');
          
          
            $local_variables = array(
                'close_button' => esc_html__('Close', 'demo-awesome'),
                'back_button' => esc_html__('Back', 'demo-awesome'),
                'next_button' => esc_html__('Next', 'demo-awesome'),
                'import_button' => esc_html__('I Understand, Begin Import', 'demo-awesome'),
                'plugin_url' => plugin_dir_url(__FILE__),
                'website_url' => get_site_url(),
                'admin_url' => admin_url(),
                'plugin_home_url' => esc_url('https://theme4press.com/'),
                'is_premium_version' => Demo_Awesome_Admin::is_premium_theme(),
                'is_free_version' => Demo_Awesome_Admin::is_free_theme(),
                'is_theme4press_theme' => Demo_Awesome_Admin::is_theme4press_theme(),
                '_nonce'    => wp_create_nonce('demo-awesome')
            );

            wp_localize_script('demo-awesome', 'demo_awesome_js_local_vars', $local_variables);
        }
    }
}
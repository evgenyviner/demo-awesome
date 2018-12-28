<?php
/**
 * Demo Awesome importer based on WordPress importer core
 *
 * Code adapted from the "Widget Importer & Exporter" plugin.
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

/**
 * Customizer Demo Importer Setting class.
 * @see WP_Customize_Setting
 */
if ( ! class_exists( 'Demo_Awesome_Customize_Demo_Importer_Setting' ) ) {
	final class Demo_Awesome_Customize_Demo_Importer_Setting extends WP_Customize_Setting {

		/**
		 * Import an option value for this setting.
		 *
		 * @param mixed $value The value to update.
		 */
		public function import( $value ) {
			$this->update( $value );
		}
	}
}
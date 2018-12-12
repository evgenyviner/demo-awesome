<?php
/**
 * Customize API: Demo_Awesome_Customize_Demo_Importer_Setting class
 *
 * @package Demo_Awesome_Importer/Classes
 * @version 1.0.0
 */

/**
 * Customizer Demo Importer Setting class.
 * @see WP_Customize_Setting
 */
final class Demo_Awesome_Customize_Demo_Importer_Setting extends WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 * @param mixed $value The value to update.
	 */
	public function import( $value ) {
		$this->update( $value );
	}
}
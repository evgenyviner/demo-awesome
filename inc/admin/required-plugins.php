<?php
/**
 * Required plugins list shown in the pop up
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if ( ! function_exists( 'demo_awesome_required_plugins' ) ) {
	function demo_awesome_required_plugins( $data_demo = array() ) {
		$show_required_description = false;
		$has_required              = false;

		if ( isset( $data_demo['plugins'] ) && $data_demo['plugins'] ) {
			$has_required = true;
		} ?>

        <div class="refresh-container-box">

            <div class="alert required-plugins-text<?php if ( ! $has_required ) {
				echo ' hide';
			} ?>" role="alert">
                <span class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'warning' ); ?></span><?php _e( 'This demo import requires additional plugins', 'demo-awesome' ); ?>
                <button class="button button-primary refresh-required"><span
                            class="mr-1"
                            name="refresh-required"><?php echo Demo_Awesome_Admin::get_svg( 'refresh' ); ?></span><?php _e( 'Refresh', 'demo-awesome' ); ?>
                </button>
            </div>

            <div class="refresh-container">
				<?php
				if ( $has_required ) {
					?>
                    <ul class="required-plugins">
						<?php
						if ( $has_required ) {
							foreach ( $data_demo['plugins'] as $plugin_keyword => $plugin ) {

								$premium_plugin = '';
								if ( Demo_Awesome_Admin::is_plugin_activated( $plugin_keyword ) == true ) {
									$required_plugin = sprintf( '<span class="badge badge-success mr-1"><span class="mr-1">%1$s</span>%2$s</span>', Demo_Awesome_Admin::get_svg( 'check' ), esc_html__( 'Active', 'demo-awesome' ) );
								} elseif ( Demo_Awesome_Admin::is_plugin_installed( $plugin_keyword ) == true && Demo_Awesome_Admin::is_plugin_activated( $plugin_keyword ) == false ) {
									$required_plugin           = sprintf( '<span class="badge badge-error mr-1"><span class="mr-1">%1$s</span>%2$s</span>', Demo_Awesome_Admin::get_svg( 'error' ), esc_html__( 'Not active', 'demo-awesome' ) );
									$premium_plugin            = sprintf( '<a class="button button-proceed" target="_blank" href="' . get_admin_url() . 'plugins.php">%s</a>', esc_html__( 'Activate', 'demo-awesome' ) );
									$show_required_description = true;
								} else {
									$required_plugin = sprintf( '<span class="badge badge-error mr-1"><span class="mr-1">%1$s</span>%2$s</span>', Demo_Awesome_Admin::get_svg( 'error' ), esc_html__( 'Not installed', 'demo-awesome' ) );
									if ( isset( $plugin['follow_download'] ) && $plugin['follow_download'] ) {
										$premium_plugin = sprintf( '<div class="badge badge-premium">%s</div>', $plugin['disable_description'] );
									} else {
										$premium_plugin = sprintf( '<a class="button button-proceed thickbox" href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=%s&TB_iframe=true&width=640&height=500">%s</a>', $plugin['keyword'], esc_html__( 'Install', 'demo-awesome' ) );
									}
									$show_required_description = true;
								}

								?>
                                <li>
                                    <strong><?php
										echo $plugin['name'] . '<br />' . $required_plugin; ?></strong><?php echo $premium_plugin; ?>
                                </li>
								<?php
							}
						} ?>
                    </ul>
					<?php if ( $show_required_description ) { ?>
                        <p class="alert alert-info required-description-text"><span
                                    class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'info' ); ?></span><?php _e( 'You can install/activate the required plugins before import or import the demo content now. Importing content without enabled required plugins may result in broken page layout', 'demo-awesome' ); ?>
                        </p>
					<?php }
				} ?>
            </div>
        </div>

		<?php
	}
}
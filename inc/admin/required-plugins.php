<?php

function demo_awesome_required_plugins( $data_demo = array() ) {
    $has_required = false;
    if ( isset( $data_demo['plugins'] ) && $data_demo['plugins'] ) {
        $has_required = true;
    }
	?>
    <div class="refresh-container-box">
        
        <div <?php if(!$has_required){ echo ('style="display: none;"'); }?> class="alert required-plugins-text" role="alert">
			<?php _e( 'This demo import requires additional plugins', 'demo-awesome' ); ?>
            <button class="button button-primary refresh-required"><span
                        class="dashicons dashicons-update mr-1"
                        name="refresh-required"></span><?php _e( 'Refresh', 'demo-awesome' ); ?></button>
        </div>

        <div class="refresh-container">
            <?php
                if ( $has_required ) {
            ?>
            <ul class="required-plugins">
				<?php
				if ( $has_required ) {
					foreach ( $data_demo['plugins'] as $plugin_keyword => $plugin ) {
						if ( class_exists( $plugin_keyword ) ) {
							$demo_awesome_woocommerce_plugin = sprintf( '<span class="badge badge-success mr-1"><span class="dashicons dashicons-yes mr-1"></span>%s</span>', __( 'Installed', 'demo-awesome' ) );
						} else {
							$demo_awesome_woocommerce_plugin = sprintf( '<span class="badge badge-error mr-1"><span class="dashicons dashicons-no-alt mr-1"></span>%s</span>', __( 'Not installed', 'demo-awesome' ) );
							if ( isset( $plugin['follow_download'] ) && $plugin['follow_download'] ) {
								$demo_awesome_woocommerce_install = sprintf( '<div class="badge badge-premium">%s</div>', __( $plugin['disable_description'], 'demo-awesome' ) );
							} else {
								$demo_awesome_woocommerce_install = sprintf( '<a class="button button-proceed" target="_blank" href="' . get_admin_url() . 'plugin-install.php?s=%s&tab=search&type=term">%s</a>', $plugin['keyword'], __( 'Install', 'demo-awesome' ) );
							}
						}
						?>
                        <li>
                            <strong><?php
								_e( $plugin['name'], 'demo-awesome' );
								echo '<br />' . $demo_awesome_woocommerce_plugin; ?></strong><?php echo $demo_awesome_woocommerce_install; ?>
                        </li>
						<?php
					}
				} ?>
            </ul>

            <p class="alert alert-info required-description-text"><span
                        class="dashicons dashicons-info mr-1"></span><?php _e( 'You can install the required plugins before import or import the demo content now. Importing content without enabled required plugins may result in broken page layout', 'demo-awesome' ); ?>
            </p>
            <?php
            } else{
                ?>
            <p class="alert alert-info required-description-text"><span
                        class="dashicons dashicons-info mr-1"></span><?php _e( 'Have not the required plugins', 'demo-awesome' ); ?>
            </p>
                <?php
            }
            ?>
        </div>
    </div>

	<?php
}


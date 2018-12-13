<?php

function demo_awesome_required_plugins($data_demo = array()) {

	$demo_awesome_woocommerce_plugin  = $demo_awesome_shortcodes_plugin = $demo_awesome_revslider_plugin = '';
	$demo_awesome_woocommerce_install = $demo_awesome_shortcodes_install = $demo_awesome_revslider_install = '';

	if ( class_exists( 'Woocommerce' ) ) {
		$demo_awesome_woocommerce_plugin = sprintf( '<span class="badge badge-success mr-1"><span class="dashicons dashicons-yes mr-1"></span>%s</span>', __( 'Installed', 'demo-awesome' ) );
	} else {
		$demo_awesome_woocommerce_plugin  = sprintf( '<span class="badge badge-error mr-1"><span class="dashicons dashicons-no-alt mr-1"></span>%s</span>', __( 'Not installed', 'demo-awesome' ) );
		$demo_awesome_woocommerce_install = sprintf( '<a class="button button-proceed" target="_blank" href="' . get_admin_url() . 'plugin-install.php?s=woocommerce&tab=search&type=term">%s</a>', __( 'Install', 'demo-awesome' ) );
	}

	if ( class_exists( 'Woocommerce' ) ) {
		$demo_awesome_shortcodes_plugin = '';
	}

	if ( class_exists( 'RevSlider' ) ) {
		$demo_awesome_revslider_plugin = sprintf( '<span class="badge badge-success mr-1"><span class="dashicons dashicons-yes mr-1"></span>%s</span>', __( 'Installed', 'demo-awesome' ) );
	} else {
		$demo_awesome_revslider_plugin  = sprintf( '<span class="badge badge-error mr-1"><span class="dashicons dashicons-no-alt mr-1"></span>%s</span>', __( 'Not installed', 'demo-awesome' ) );
		$demo_awesome_revslider_install = sprintf( '<div class="badge">%s</div>', __( 'Premium plugin - please visit your Theme4Press account to download it', 'demo-awesome' ) );
	}
	?>
    <div class="refresh-container-box">
        <div class="alert required-plugins-text" role="alert">
    		<?php _e( 'This demo import requires additional plugins', 'demo-awesome' ); ?>
            <button class="button button-primary refresh-required"><span
                        class="dashicons dashicons-update mr-1"
                        name="refresh-required"></span><?php _e( 'Refresh', 'demo-awesome' ); ?></button>
        </div>

        <div class="refresh-container">

            <ul class="required-plugins">
                <li>
                    <strong><?php echo $demo_awesome_woocommerce_plugin;
    					_e( 'WooCommerce', 'demo-awesome' ); ?></strong><?php echo $demo_awesome_woocommerce_install; ?>
                </li>

                <li><strong><?php echo $demo_awesome_revslider_plugin;
    					_e( 'Revolution Slider', 'demo-awesome' ); ?></strong><?php echo $demo_awesome_revslider_install; ?>
                </li>
            </ul>

            <ul class="required-plugins-list">
                <li>
                    <strong><?php echo $demo_awesome_woocommerce_plugin;
    					_e( 'WooCommerce', 'demo-awesome' ); ?></strong>
                </li>
                <li>
                    <strong><?php echo $demo_awesome_shortcodes_plugin;
    					_e( 'Theme4Press Shortcodes', 'demo-awesome' ); ?></strong>
                </li>
                <li>
                    <strong><?php echo $demo_awesome_revslider_plugin;
    					_e( 'Revolution Slider', 'demo-awesome' ); ?></strong>
                </li>
            </ul>

            <p class="alert alert-info required-description-text"><span
                        class="dashicons dashicons-info mr-1"></span><?php _e( 'You can install the required plugins before import or import the demo content now. Importing content without enabled required plugins may result in broken page layout.', 'demo-awesome' ); ?>
            </p>

        </div>
    </div>

	<?php
}


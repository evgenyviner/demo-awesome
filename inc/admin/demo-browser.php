<?php
if ( $demo_awesome_get_list_demos ) {
	$demo_awesome_get_list_demos = json_decode( $demo_awesome_get_list_demos, true );
}

$demo_awesome_my_theme = wp_get_theme();
?>
<ul class="nav" role="tablist">
    <li class="nav-item"><a class="demo-filter nav-link active" data-toggle="pill" role="tab"
                            data-filter="filter-all" href="#"
                            aria-selected="true"><?php _e( 'All', 'demo-awesome' ); ?></a></li>
	<?php if ( $demo_awesome_get_list_demos ) {
		foreach ( $demo_awesome_get_list_demos as $demo_awesome_item_key => $demo_awesome_item ) { ?>
            <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab"
                                    data-filter="<?php echo esc_attr( $demo_awesome_item_key ); ?>"
                                    href="#" aria-selected="false"><?php _e( $demo_awesome_item['name'] ); ?></a>
            </li>
		<?php }
	} ?>
</ul>

<div class="theme-demos wp-clearfix">

	<?php
	$demo_awesome_index_temp_demo = 0;
	if ( $demo_awesome_get_list_demos ) {
		foreach ( $demo_awesome_get_list_demos as $demo_awesome_item_key => $demo_awesome_item ) {
			if ( isset( $demo_awesome_item['items'] ) && $demo_awesome_item['items'] ) {
				foreach ( $demo_awesome_item['items'] as $demo_awesome_item_key_2 => $demo_awesome_item_2 ) {
					$demo_awesome_index_temp_demo ++;
					?>
                    <div class="demo filter-all <?php echo esc_attr( $demo_awesome_item_key ); ?>"
                         tabindex="0"
                         aria-describedby="demo-action demo-name"
                         data-demo-show="<?php echo esc_attr( json_encode( $demo_awesome_item_2 ) ); ?>">
                        <div class="demo-screenshot" data-toggle="modal" data-backdrop="static"
                             data-target="#details-modal">
                            <img src="<?php echo "https://demo.theme4press.com/demo-import/" . $demo_awesome_item_key . "/" . ( $demo_awesome_item_key_2 + 1 ) . "/screenshot.png"; ?>"
                                 alt=""/>
                            <span class="more-details" id="demo-action"></span>
                        </div>

                        <div class="demo-container">
                            <h2 class="demo-name" id="demo-name"><?php _e( $demo_awesome_item_2['name'] ); ?></h2>

                            <div class="demo-actions"
                                 data-index="<?php echo esc_attr( $demo_awesome_index_temp_demo ); ?>">
                                <a href="#" role="button" class="button import call-import-demo-function"
                                   data-toggle="modal" data-backdrop="static"
                                   data-target="#import-modal"
                                   aria-label="<?php _e( 'Import Demo', 'demo-awesome' ); ?>"><?php _e( 'Import', 'demo-awesome' ); ?></a>
								<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                                    <a class="button button-primary load-preview"
                                       href=""><?php _e( 'Live Preview', 'demo-awesome' ); ?></a>
								<?php } ?>
                            </div>
                        </div>
                    </div>
					<?php
				}
			}
		}
	} ?>

    <!-- Import Demo Modal -->
    <div class="modal fade" id="import-modal" tabindex="-1" role="dialog"
         aria-labelledby="import-modal-label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="import-modal-label"><?php _e( 'Import Demo - ', 'demo-awesome' ); ?>
                        <span></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body wizard-content">
                    <div class="wizard-step">

						<?php _e( 'Minimum recommended theme version for this demo: ', 'demo-awesome' ); ?><span
                                class="theme-required-version"></span><br/>
                        <h3><?php _e( 'Installed theme version: ' . $demo_awesome_my_theme['Version'], 'demo-awesome' ); ?></h3>

						<?php
						require dirname( __FILE__ ) . '/required-plugins.php';
						demo_awesome_required_plugins(); ?>

                        <p><strong><?php _e( 'Step 1 of 2', 'demo-awesome' ); ?></strong></p>

                    </div>

                    <div class="wizard-step">
                        <div class="alert" role="alert">
							<?php _e( 'The demo import will overwrite many website options - theme settings, menus, widgets, posts/pages', 'demo-awesome' ); ?>
                        </div>

                        <p><?php _e( 'Please proceed with a caution. The demo import is recommended for new websites and it will replicate the demo website which you can see in the preview mode.', 'demo-awesome' ); ?></p>

                        <p><strong><?php _e( 'Step 2 of 2', 'demo-awesome' ); ?></strong></p>

                    </div>
                </div>
                <div class="modal-footer wizard-buttons">
                </div>
            </div>
        </div>
    </div>

    <!-- Importing & Finish Demo Modal -->
    <div class="modal fade" id="finish-import-modal" tabindex="-1" role="dialog"
         aria-labelledby="finish-import-modal-label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="finish-import-modal-label"><?php _e( 'Importing Demo - ', 'demo-awesome' ); ?><span></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="alert hide-content" role="alert">
						<?php _e( 'Please don\'t leave/refresh the page while importing', 'demo-awesome' ); ?>
                    </div>

                    <h3 class="hide-content"><img
                                src="<?php echo get_site_url() . '/wp-admin/images/spinner.gif'; ?>"/> <?php _e( 'Importing the demo...', 'demo-awesome' ); ?>
                    </h3>


                    <h3 class=" hide show-content"><span
                                class="dashicons dashicons-yes"></span> <?php _e( 'Import finished', 'demo-awesome' ); ?>
                    </h3>

                    <div class="alert alert-success hide show-content" role="alert">
						<?php _e( 'The demo has been imported successfully', 'demo-awesome' ); ?>
                    </div>

                </div>

                <div class="modal-footer hide show-content">
                    <a type="button" class="button back"
                       href="<?php echo admin_url( "customize.php" ); ?>"><?php _e( 'Customize', 'demo-awesome' ); ?></a>
                    <button type="button" class="button button-primary" data-dismiss="modal"
                            aria-label="Close"><?php _e( 'Close', 'demo-awesome' ); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="details-modal" tabindex="-1" role="dialog"
         aria-labelledby="details-modal-label"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="details-modal-label"><?php _e( 'Demo Details - ', 'demo-awesome' ); ?>
                        <span></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="demo-info-row">

                        <div class="demo-info-col demo-screenshot-container">
                            <img src="<?php echo "https://demo.theme4press.com/demo-import/business/1/screenshot.png"; ?>"
                                 alt=""/>
                        </div>

                        <div class="demo-info-col">
							<?php _e( 'Minimum recommended theme version for this demo: ', 'demo-awesome' ); ?><span
                                    class="theme-required-version"></span><br/>
                            <h3><?php _e( 'Installed theme version: ' . $demo_awesome_my_theme['Version'], 'demo-awesome' ); ?></h3>


                            <div class="alert required-plugins-text" role="alert">
								<?php _e( 'This demo import requires additional plugins', 'demo-awesome' ); ?>
                            </div>

                            <ul class="required-plugins-list">
                                <li>
                                    <strong><?php
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

                            <p class="required-description-text"><?php _e( 'You can install the required plugins before import or import the demo content now. Importing content without enabled required plugins may result in broken page layout.', 'demo-awesome' ); ?></p>

                            <div class="demo-actions"><a href="#" role="button" data-toggle="modal"
                                                         data-backdrop="static"
                                                         data-target="#details-modal">
                                </a><a class="button import call-import-demo-function" data-dismiss="modal"
                                       data-toggle="modal"
                                       data-backdrop="static"
                                       data-target="#import-modal" href=""
                                       aria-label="Import Demo"><?php _e( 'Import', 'demo-awesome' ); ?></a>
                                <a class="button button-primary load-preview"
                                   href=""><?php _e( 'Live Preview', 'demo-awesome' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
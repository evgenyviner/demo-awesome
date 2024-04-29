<?php
/**
 * Displays the list of the available demos
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if ( $demo_awesome_get_list_demos ) {
	$demo_awesome_get_list_demos = json_decode( $demo_awesome_get_list_demos, true );
}

$demo_awesome_my_theme = wp_get_theme();

require dirname( __FILE__ ) . '/required-plugins.php'; ?>

<ul class="nav" role="tablist">
    <li class="nav-item"><a class="demo-filter nav-link active" data-toggle="pill" role="tab"
                            data-filter="filter-all" href="#"
                            aria-selected="true"><?php esc_html_e( 'All', 'demo-awesome' ); ?></a></li>
	<?php if ( $demo_awesome_get_list_demos ) {
		foreach ( $demo_awesome_get_list_demos as $demo_awesome_item_key => $demo_awesome_item ) { ?>
            <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab"
                                    data-filter="<?php echo esc_attr( $demo_awesome_item_key ); ?>"
                                    href="#"
                                    aria-selected="false"><?php esc_html_e( $demo_awesome_item['name'] ); ?></a>
            </li>
		<?php }
	} ?>
</ul>

<div class="theme-demos wp-clearfix">

	<?php
	$demo_awesome_index_temp_demo = 0;
	if ( $demo_awesome_get_list_demos ) {
		$demo_awesome_list_demos             = array();
		$demo_awesome_list_demos_sorted      = array();
		$demo_awesome_list_demos_sorted_keys = array();
		if ( Demo_Awesome_Admin::is_free_theme() ) {
			foreach ( $demo_awesome_get_list_demos as $demo_awesome_item_key => $demo_awesome_item ) {
				if ( isset( $demo_awesome_item['items'] ) && $demo_awesome_item['items'] ) {
					foreach ( $demo_awesome_item['items'] as $demo_awesome_item_key_2 => $demo_awesome_item_2 ) {
						$demo_awesome_item_2['filter_key']                        = $demo_awesome_item_key;
						$demo_awesome_list_demos[ $demo_awesome_item_2['index'] ] = $demo_awesome_item_2;
					}
				}
			}
			$demo_awesome_number_demos = count( $demo_awesome_list_demos );
			for ( $i = 0; $i < $demo_awesome_number_demos; $i ++ ) {
				$demo_awesome_list_demos_sorted[] = $demo_awesome_list_demos[ $i ];
			}
		}

		foreach ( $demo_awesome_get_list_demos as $demo_awesome_item_key => $demo_awesome_item ) {
			if ( isset( $demo_awesome_item['items'] ) && $demo_awesome_item['items'] ) {
				foreach ( $demo_awesome_item['items'] as $demo_awesome_item_key_2 => $demo_awesome_item_2 ) {
					if ( $demo_awesome_list_demos_sorted && count( $demo_awesome_list_demos_sorted ) ) {
						$demo_awesome_item_2   = $demo_awesome_list_demos_sorted[ $demo_awesome_index_temp_demo ];
						$demo_awesome_item_key = $demo_awesome_list_demos_sorted[ $demo_awesome_index_temp_demo ]['filter_key'];
					}
					$demo_awesome_index_temp_demo ++;
					$demo_awesome_premium_demo = ( isset( $demo_awesome_item_2['premium_demo'] ) && $demo_awesome_item_2['premium_demo'] ) ? true : false;
					?>
                    <div class="demo demo-awesome-container filter-all <?php echo esc_attr( $demo_awesome_item_key ); ?>"
                         tabindex="0"
                         aria-describedby="demo-action demo-name"
                         data-demo-show="<?php echo esc_attr( json_encode( $demo_awesome_item_2 ) ); ?>">
						<?php if ( $demo_awesome_premium_demo && Demo_Awesome_Admin::is_free_theme() ) { ?>
                            <div class="badge badge-demo"><?php esc_html_e( 'PREMIUM', 'demo-awesome' ); ?></div><?php } ?>
                        <div class="demo-screenshot" data-toggle="modal" data-backdrop="static"
                             data-target="#details-modal">
                            <img src="<?php echo esc_url(DEMO_AWESOME_IMPORTER_SOURCE_URL . $demo_awesome_item_2['folder_path'] . "/screenshot.png"); ?>"
                                 alt=""/>
                            <span class="more-details" id="demo-action"></span>
                        </div>

                        <div class="demo-container">
                            <h2 class="demo-name"
                                id="demo-name"><?php esc_html_e( $demo_awesome_item_2['name'] ); ?></h2>

                            <div class="demo-actions"
                                 data-index="<?php echo esc_attr( $demo_awesome_index_temp_demo ); ?>">
								<?php if ( Demo_Awesome_Admin::is_premium_theme() || ! $demo_awesome_premium_demo && Demo_Awesome_Admin::is_free_theme() ) { ?>
                                    <a href="#"
                                       role="button"
                                       class="button import call-import-demo-function"
                                       data-toggle="modal"
                                       data-backdrop="static"
                                       data-target="#import-modal"
                                       aria-label="<?php esc_html_e( 'Import', 'demo-awesome' ); ?>"><?php esc_html_e( 'Import', 'demo-awesome' ); ?></a><?php } ?>
								<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                                    <a href="#" role="button" class="button button-primary load-preview"
                                       data-toggle="modal" data-backdrop="static"
                                       data-target="#preview-modal"
                                       aria-label="<?php esc_html_e( 'Live Preview', 'demo-awesome' ); ?>"><?php esc_html_e( 'Live Preview', 'demo-awesome' ); ?></a>
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
                    <h5 class="modal-title"
                        id="import-modal-label"><?php esc_html_e( 'Import Demo - ', 'demo-awesome' ); ?>
                        <span></span></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body wizard-content">
                    <div class="wizard-step">

						<?php esc_html_e( 'Minimum recommended theme version for this demo: ', 'demo-awesome' ); ?><span
                                class="theme-required-version"></span><br/>
                        <h3><?php echo  esc_html( sprintf( esc_html__( 'Installed theme version: %s', 'demo-awesome' ), $demo_awesome_my_theme['Version'] )); ?></h3>

						<?php if ( Demo_Awesome_Admin::is_theme4press_theme() ) {
							demo_awesome_required_plugins();
						} ?>

                        <p><strong><?php esc_html_e( 'Step 1 of 2', 'demo-awesome' ); ?></strong></p>

                    </div>

                    <div class="wizard-step">
                        <div class="alert" role="alert">
                            <span class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'warning' ); ?></span><?php echo sprintf( ( '%1$s%2$s%3$s%4$s%5$s%6$s%7$s%8$s%9$s%10$s%11$s%12$s%13$s' ), esc_html__( 'The demo import may overwrite/remove many website options', 'demo-awesome' ), '<br /><br />- ', esc_html__( 'customizer settings', 'demo-awesome' ), '<br />- ', esc_html__( 'menus', 'demo-awesome' ), '<br />- ', esc_html__( 'widgets', 'demo-awesome' ), '<br />- ', esc_html__( 'posts/pages', 'demo-awesome' ), '<br />- ', esc_html__( 'set new front page', 'demo-awesome' ), '<br /><br />', esc_html__( 'Some demos can take few minutes to complete the import, depending on the complexity of the demo content.', 'demo-awesome' ) ); ?>
                        </div>

                        <p><?php echo esc_html__( 'Please proceed with a caution. The demo import is recommended for new websites and it will replicate the demo website which you can see in the preview mode.', 'demo-awesome' ); ?></p>

                        <p><strong><?php esc_html_e( 'Step 2 of 2', 'demo-awesome' ); ?></strong></p>

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
                        id="finish-import-modal-label"><?php esc_html_e( 'Importing Demo - ', 'demo-awesome' ); ?>
                        <span></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="alert hide-content" role="alert">
                        <span class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'warning' ); ?></span><?php esc_html_e( 'Please don\'t leave/refresh the page while importing. Some demos can take few minutes to complete the import, depending on the complexity of the demo content', 'demo-awesome' ); ?>
                    </div>

                    <h3 class="hide-content"><?php echo '<span class="svg-spin mr-1">' . Demo_Awesome_Admin::get_svg( 'loader' ) . '</span>';
						esc_html_e( 'Importing the demo...', 'demo-awesome' ); ?>
                    </h3>

                    <div class="alert alert-success alert-message-from-importer hide show-content" role="alert">
                        <h3><?php echo '<span class="mr-1">' . Demo_Awesome_Admin::get_svg( 'check' ) . '</span>';
							esc_html_e( 'Import finished', 'demo-awesome' ); ?>
                        </h3>
						<?php esc_html_e( 'The demo has been imported successfully', 'demo-awesome' ); ?>
                    </div>

                </div>

                <div class="modal-footer hide show-content">
                    <a type="button" class="button back" target="_blank"
                       href="<?php echo site_url(); ?>"><?php esc_html_e( 'View Website', 'demo-awesome' ); ?></a>
                    <a type="button" class="button back"
                       href="<?php echo admin_url( "customize.php" ); ?>"><?php esc_html_e( 'Customize', 'demo-awesome' ); ?></a>
                    <a role="button" class="button button-primary"
                       data-dismiss="modal" href="#"
                       aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>"><?php esc_html_e( 'Close', 'demo-awesome' ); ?></a>
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
                    <h5 class="modal-title"
                        id="details-modal-label"><?php esc_html_e( 'Demo Details - ', 'demo-awesome' ); ?>
                        <span></span></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="demo-info-row demo-awesome-container">

                        <div class="demo-info-col demo-screenshot-container">
							<?php if ( Demo_Awesome_Admin::is_free_theme() ) { ?>
                                <div class="badge badge-demo demo-awesome-premium-badge"><?php esc_html_e( 'PREMIUM', 'demo-awesome' ); ?></div>
							<?php } ?>
                            <img src="" alt=""/>
                        </div>

                        <div class="demo-info-col">
                            <div class="required-theme-version">
								<?php esc_html_e( 'Minimum recommended theme version for this demo: ', 'demo-awesome' ); ?>
                                <span class="theme-required-version"></span><br/>
                                <h3><?php echo sprintf( esc_html__( 'Installed theme version: %s', 'demo-awesome' ), $demo_awesome_my_theme['Version'] ); ?></h3>
                            </div>

							<?php if ( Demo_Awesome_Admin::is_premium_theme() || Demo_Awesome_Admin::is_free_theme() ) {
								demo_awesome_required_plugins();
							}
							if ( Demo_Awesome_Admin::is_free_theme() ) { ?>
                                <p class="alert alert-info required-premium-text"><span
                                            class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'info' ); ?></span><span><?php echo sprintf( esc_html__( 'This demo requires the %1$sPremium%2$s version of the theme', 'demo-awesome' ), '<strong>', '</strong>' ); ?></span>
                                    <a class="button button-primary demo-name-link-1"
                                       target="_blank"
                                       href=""><?php esc_html_e( 'More', 'demo-awesome' ); ?>
                                    </a>
                                </p>
							<?php }
							if ( ! Demo_Awesome_Admin::is_theme4press_theme() ) { ?>
                                <p class="alert alert-info required-premium-text"><span
                                            class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'info' ); ?></span><?php echo Demo_Awesome_Admin::is_theme4press_theme_message(); ?>
                                </p>
							<?php } ?>

                            <div class="demo-actions">
                                <a href="#" role="button" class="button proceed close-premium"
                                   data-dismiss="modal"
                                   aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>"><?php esc_html_e( 'Close', 'demo-awesome' ); ?></a>
								<?php if ( Demo_Awesome_Admin::is_theme4press_theme() ) { ?>
                                    <a href="#"
                                       role="button"
                                       class="button import call-import-demo-function"
                                       data-dismiss="modal"
                                       data-toggle="modal"
                                       data-backdrop="static"
                                       data-target="#import-modal"
                                       aria-label="<?php esc_html_e( 'Import', 'demo-awesome' ); ?>"><?php esc_html_e( 'Import', 'demo-awesome' ); ?></a>
								<?php } ?>
                                <a href="#" role="button" class="button button-primary load-preview"
                                   data-dismiss="modal"
                                   data-toggle="modal" data-backdrop="static"
                                   data-target="#preview-modal"
                                   aria-label="<?php esc_html_e( 'Live Preview', 'demo-awesome' ); ?>"><?php esc_html_e( 'Live Preview', 'demo-awesome' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Preview -->
    <div class="modal modal-preview fade" id="preview-modal" tabindex="-1" role="dialog"
         aria-labelledby="preview-modal-label"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="details-modal-label"><?php esc_html_e( 'Live Preview - ', 'demo-awesome' ); ?>
                        <span></span></h5>
                    <div class="btn-group" role="group"
                         aria-label="<?php esc_html_e( 'Collapse Panel', 'demo-awesome' ); ?>">
                        <button type="button"
                                class="button button-primary toggle-sidebar ml-2"><span
                                    class="text"><?php esc_html_e( 'Collapse Panel', 'demo-awesome' ); ?></span>
                            <i class="demo-awesome-icon-right"></i>
                        </button>
                    </div>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="demo-info-row demo-preview-row demo-awesome-container">

                        <div class="demo-info-col demo-preview-container">
                            <div class="loader"></div>
                            <iframe src="" frameborder="0" marginwidth="0"
                                    marginheight="0"
                                    scrolling="auto" allowfullscreen>
                            </iframe>
                        </div>

                        <div class="demo-info-col demo-required-plugins demo-required-plugins-preview">
                            <div class="required-theme-version">
								<?php esc_html_e( 'Minimum recommended theme version for this demo: ', 'demo-awesome' ); ?>
                                <span class="theme-required-version"></span><br/>
                                <h3><?php echo sprintf( esc_html__( 'Installed theme version: %s', 'demo-awesome' ), $demo_awesome_my_theme['Version'] ); ?></h3>
                            </div>

							<?php if ( Demo_Awesome_Admin::is_premium_theme() || Demo_Awesome_Admin::is_free_theme() ) {
								demo_awesome_required_plugins();
							}
							if ( Demo_Awesome_Admin::is_free_theme() ) { ?>
                                <p class="alert alert-info required-premium-text"><span
                                            class="mr-1"><?php echo Demo_Awesome_Admin::get_svg( 'info' ); ?></span><span><?php echo sprintf( esc_html__( 'This demo requires the %1$sPremium%2$s version of the theme', 'demo-awesome' ), '<strong>', '</strong>' ); ?></span>
                                    <a class="button button-primary demo-name-link-2"
                                       target="_blank"
                                       href=""><?php esc_html_e( 'More', 'demo-awesome' ); ?>
                                    </a>
                                </p>
							<?php }
							if ( ! Demo_Awesome_Admin::is_theme4press_theme() ) { ?>
                                <p class="alert alert-info required-premium-text"><span
                                            class="mr-1"><?php echo wp_kses_post( Demo_Awesome_Admin::get_svg( 'info' )); ?></span>
                                    <?php echo wp_kses_post(Demo_Awesome_Admin::is_theme4press_theme_message()); ?>
                                </p>
							<?php } ?>

                            <div class="demo-actions">
								<?php if ( Demo_Awesome_Admin::is_theme4press_theme() ) { ?>
                                    <button type="button"
                                            class="button import call-import-demo-function" data-dismiss="modal"
                                            data-toggle="modal"
                                            data-backdrop="static"
                                            data-target="#import-modal"
                                            aria-label="<?php esc_html_e( 'Import', 'demo-awesome' ); ?>"><?php esc_html_e( 'Import', 'demo-awesome' ); ?></button>
								<?php } ?>
                                <button type="button" class="button button-primary" data-dismiss="modal"
                                        aria-label="<?php esc_html_e( 'Close', 'demo-awesome' ); ?>"><?php esc_html_e( 'Close', 'demo-awesome' ); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
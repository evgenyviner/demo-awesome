

    <ul class="nav" role="tablist">
        <li class="nav-item"><a class="demo-filter nav-link active" data-toggle="pill" role="tab"
                                data-filter="filter-all" href="#" aria-selected="true"><?php _e( 'All' ); ?></a></li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab" data-filter="app"
                                href="#" aria-selected="false"><?php _e( 'App' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab" data-filter="blog"
                                href="#" aria-selected="false"><?php _e( 'Blog' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab" data-filter="business"
                                href="#" aria-selected="false"><?php _e( 'Business' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab"
                                data-filter="eshop" href="#" aria-selected="false"><?php _e( 'eShop' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab"
                                data-filter="magazine" href="#" aria-selected="false"><?php _e( 'Magazine' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab" data-filter="personal"
                                href="#" aria-selected="false"><?php _e( 'Personal' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab"
                                data-filter="photography"
                                href="#" aria-selected="false"><?php _e( 'Photography' ); ?></a>
        </li>
        <li class="nav-item"><a class="demo-filter nav-link" data-toggle="pill" role="tab" data-filter="portfolio"
                                href="#" aria-selected="false"><?php _e( 'Portfolio' ); ?></a>
        </li>
    </ul>

    <div class="theme-demos wp-clearfix">

        <div class="demo filter-all business"
             tabindex="0"
             aria-describedby="business-demo-action business-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/business/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="business-demo-name"><?php _e( 'Business' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>

        <div class="demo filter-all magazine"
             tabindex="0"
             aria-describedby="magazine-demo-action magazine-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/magazine/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="magazine-demo-name"><?php _e( 'Magazine' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Magazine Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>


        <div class="demo filter-all app"
             tabindex="0" aria-describedby="app-demo-action app-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/app/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="app-demo-name"><?php _e( 'App' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import App Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>

        <div class="demo filter-all portfolio"
             tabindex="0"
             aria-describedby="portfolio-demo-action portfolio-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/portfolio/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="portfolio-demo-name"><?php _e( 'Portfolio' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>

        <div class="demo filter-all eshop"
             tabindex="0" aria-describedby="eshop-demo-action eshop-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/eshop/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="eshop-demo-name"><?php _e( 'eShop' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>

        <div class="demo filter-all business"
             tabindex="0"
             aria-describedby="business-2-demo-action business-2-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/business/2/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="business-2-demo-name"><?php _e( 'Business 2' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>


        <div class="demo filter-all personal"
             tabindex="0"
             aria-describedby="personal-demo-action personal-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/personal/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="personal-demo-name"><?php _e( 'Personal' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>


        <div class="demo filter-all photography"
             tabindex="0"
             aria-describedby="photography-demo-action photography-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/photography/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="photography-demo-name"><?php _e( 'Photography' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>


        <div class="demo filter-all blog"
             tabindex="0" aria-describedby="blog-demo-action blog-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/blog/1/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="blog-demo-name"><?php _e( 'Blog' ); ?></h2>

                <div class="demo-actions">
                    <a href="#" role="button" class="button import" data-toggle="modal" data-backdrop="static"
                       data-target="#import-modal"
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>


        <div class="demo filter-all business"
             tabindex="0"
             aria-describedby="business-3-demo-action business-3-demo-name">
            <div class="demo-screenshot" data-toggle="modal" data-backdrop="static" data-target="#details-modal">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/business/3/screenshot.png'; ?>"
                     alt=""/>
                <span class="more-details" id="business-3-demo-action"></span>
            </div>

            <div class="demo-container">
                <h2 class="demo-name" id="business-3-demo-name"><?php _e( 'Business 3' ); ?></h2>

                <div class="demo-actions">
                    <a class="button import" data-toggle="modal" data-backdrop="static" data-target="#import-modal"
                       href=""
                       aria-label="<?php _e( 'Import Business Demo' ); ?>"><?php _e( 'Import' ); ?></a>
					<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
                        <a class="button button-primary load-preview"
                           href=""><?php _e( 'Live Preview' ); ?></a>
					<?php } ?>
                </div>
            </div>
        </div>


        <!-- Import Demo Modal -->
        <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal-label"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="import-modal-label"><?php _e( 'Import Demo - Business' ); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body wizard-content">
                        <div class="wizard-step">

							<?php _e( 'Minimum recommended theme version for this demo: 2.9' ); ?><br/>
                            <h3><?php _e( 'Installed theme version: 2.9' ); ?></h3>


                            <div class="alert" role="alert">
								<?php _e( 'This demo import requires additional plugins' ); ?>
                            </div>

                            <ol>
                                <li><strong><?php _e( 'WooCommerce' ); ?></strong></li>
                                <li><strong><?php _e( 'Theme4Press Shortcodes' ); ?></strong></li>
                                <li><strong><?php _e( 'Revolution Slider' ); ?></strong></li>
                            </ol>

                            <p><?php _e( 'You can install the required plugins before import or import the demo content now. Importing content without enabled required plugins may result in broken page layout.' ); ?></p>

                            <p><strong><?php _e( 'Step 1 of 2' ); ?></strong></p>

                        </div>

                        <div class="wizard-step">
                            <div class="alert" role="alert">
								<?php _e( 'The demo import will overwrite many website options - theme settings, menus, widgets, posts/pages' ); ?>
                            </div>

                            <p><?php _e( 'Please proceed with a caution. The demo import is recommended for new websites and it will replicate the demo website which you can see in the preview mode.' ); ?></p>

                            <p><strong><?php _e( 'Step 2 of 2' ); ?></strong></p>

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
                            id="finish-import-modal-label"><?php _e( 'Importing Demo - Business' ); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="alert hide-content" role="alert">
							<?php _e( 'Please don\'t leave/refresh the page while importing' ); ?>
                        </div>

                        <h3 class="hide-content"><img
                                    src="<?php echo get_site_url() . '/wp-admin/images/spinner.gif'; ?>"/> <?php _e( 'Importing the demo...' ); ?>
                        </h3>


                        <h3 class=" hide show-content"><span
                                    class="dashicons dashicons-yes"></span> <?php _e( 'Import finished' ); ?></h3>

                        <div class="alert alert-success hide show-content" role="alert">
							<?php _e( 'The demo has been imported successfully' ); ?>
                        </div>

                    </div>

                    <div class="modal-footer hide show-content">
                        <button type="button" class="button back"><?php _e( 'Customize' ); ?></button>
                        <button type="button" class="button button-primary" data-dismiss="modal"
                                aria-label="Close"><?php _e( 'Close' ); ?></button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Details Modal -->
        <div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-modal-label"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="details-modal-label"><?php _e( 'Demo Details - Business' ); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="demo-info-row">

                            <div class="demo-info-col">
                                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/demo-content/business/1/screenshot.png'; ?>"
                                     alt=""/>
                            </div>

                            <div class="demo-info-col">
								<?php _e( 'Minimum recommended theme version for this demo: 2.9' ); ?><br/>
                                <h3><?php _e( 'Installed theme version: 2.9' ); ?></h3>


                                <div class="alert" role="alert">
									<?php _e( 'This demo import requires additional plugins' ); ?>
                                </div>

                                <ol>
                                    <li><strong><?php _e( 'WooCommerce' ); ?></strong></li>
                                    <li><strong><?php _e( 'Theme4Press Shortcodes' ); ?></strong></li>
                                    <li><strong><?php _e( 'Revolution Slider' ); ?></strong></li>
                                </ol>

                                <p><?php _e( 'You can install the required plugins before import or import the demo content now. Importing content without enabled required plugins may result in broken page layout.' ); ?></p>

                                <div class="demo-actions"><a href="#" role="button" data-toggle="modal"
                                                             data-backdrop="static"
                                                             data-target="#details-modal">
                                    </a><a class="button import" data-dismiss="modal" data-toggle="modal"
                                           data-backdrop="static"
                                           data-target="#import-modal" href=""
                                           aria-label="Import Business Demo"><?php _e( 'Import' ); ?></a>
                                    <a class="button button-primary load-preview"
                                       href=""><?php _e( 'Live Preview' ); ?></a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

<?php

/**
 * The live preview functionality of the plugin
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
	<?php
	do_action( 'demo_awesome_wp_head' );
	wp_title();
	wp_head();
	?>
</head>
<body>
<div class="demo-preview-wrapper open">
    <div class="demo-preview-panel">

        <div class="panel-header-actions">
            <a class="close-panel" href="" aria-label="Close">
                <span aria-hidden="true">×</span>
            </a>
        </div>

        <div class="panel-document-actions display-flex">
            <h5><?php _e( 'Demo Preview - Blog 1', 'demo-awesome' ); ?></h5>
        </div>

        <div class="panel-content">
			<?php _e( 'Minimum recommended theme version for this demo: ', 'demo-awesome' ); ?><span
                    class="theme-required-version"></span><br/>
            <h3><?php $demo_awesome_my_theme = wp_get_theme();
				_e( 'Installed theme version: ' . $demo_awesome_my_theme['Version'], 'demo-awesome' ); ?></h3>

			<?php //demo_awesome_required_plugins(); ?>

            <div class="demo-actions"><a href="#" role="button" data-toggle="modal"
                                         data-backdrop="static"
                                         data-target="#details-modal">
                </a><a class="button import call-import-demo-function" data-dismiss="modal"
                       data-toggle="modal"
                       data-backdrop="static"
                       data-target="#import-modal" href=""
                       aria-label="Import Demo"><?php _e( 'Import', 'demo-awesome' ); ?></a>
                <a class="button button-primary load-preview"
                   href=""><?php _e( 'Close', 'demo-awesome' ); ?></a>
            </div>

        </div>

        <div class="panel-document-footer-actions display-flex">
        </div>

    </div>
    <div class="demo-preview-preview">
        <div class="loader">
            <div class="loader-image"></div>
        </div>

        <iframe src="<?php echo add_query_arg( array(
			'view_page' => 'demo-preview',
		), get_permalink( (int) $_REQUEST['demo-preview_id'] ) ); ?>" frameborder="0"></iframe>
    </div>
	<?php wp_footer(); ?>
</div>
</body>
</html>
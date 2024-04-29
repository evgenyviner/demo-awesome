<?php
/**
 * Required plugins list shown in the pop up
 *
 * @link       https://theme4press.com/demo-awesome-the-data-importer/
 * @since      1.0.0
 * @package    Demo Awesome
 * @author     Theme4Press
 */

if (!function_exists('demo_awesome_required_plugins')) {
    function demo_awesome_required_plugins($data_demo = array())
    {
        $show_required_description = false;
        $has_required = false;

        if (isset($data_demo['plugins']) && $data_demo['plugins']) {
            $has_required = true;
        }


        ?>

        <div class="refresh-container-box">
            <?php
            if (isset($_POST['action']) && $_POST['action'] == 'required_plugins') {
                $requred_message = [];
                if ($data_demo) {
                    foreach ($data_demo['plugins'] as $plugin_keyword => $plugin) {
                        $plugin_keyword = isset($plugin['name']) ? $plugin['name'] : '';
                        if (Demo_Awesome_Admin::is_plugin_activated($plugin_keyword) == true) {
                            $requred_message [] = true;
                        } else {
                            $requred_message [] = false;
                        }
                    }
                }

                // is some plugin don't activate we show message
                if (count(array_unique($requred_message)) !== 1) {
                    ?>
                    <div class="alert required-plugins-text<?php if (!$has_required) {
                        echo ' hide';
                    } ?>" role="alert">


                        <span class="mr-1"><?php echo Demo_Awesome_Admin::get_svg('warning'); ?></span><?php esc_html_e(
                            'This demo import requires additional plugins',
                            'demo-awesome'
                        );
                        ?>
                        <button class="button button-primary refresh-required"><span
                                    class="mr-1"
                                    name="refresh-required"><?php echo Demo_Awesome_Admin::get_svg('refresh'); ?></span>
                            <?php esc_html_e('Refresh', 'demo-awesome'); ?>
                        </button>

                    </div> <?php
                }

            } else {
                ?>
                <div class="alert required-plugins-text<?php if (!$has_required) {
                    echo ' hide';
                } ?>" role="alert">


                    <span class="mr-1"><?php echo Demo_Awesome_Admin::get_svg('warning'); ?></span><?php esc_html_e(
                        'This demo import requires additional plugins',
                        'demo-awesome'
                    );
                    ?>
                    <button class="button button-primary refresh-required"><span
                                class="mr-1"
                                name="refresh-required"><?php echo wp_kses_post( Demo_Awesome_Admin::get_svg(
                                'refresh'
                            )); ?></span><?php esc_html_e('Refresh', 'demo-awesome'); ?>
                    </button>

                </div>
            <?php } ?>

            <div class="refresh-container">
                <?php
                if ($has_required) {
                    ?>
                    <ul class="required-plugins">
                        <?php
                        if ($has_required) {
                            foreach ($data_demo['plugins'] as $plugin_keyword => $plugin) {

                                $plugin_keyword = isset($plugin['name']) ? $plugin['name'] : '';
                                $premium_plugin = '';
                                if (Demo_Awesome_Admin::is_plugin_activated($plugin_keyword) == true) {
                                    $required_plugin = sprintf(
                                        '<span class="badge badge-success mr-1"><span class="mr-1">%1$s</span>%2$s</span>',
                                        Demo_Awesome_Admin::get_svg('check'),
                                        esc_html__('Active', 'demo-awesome')
                                    );
                                } elseif (Demo_Awesome_Admin::is_plugin_installed(
                                        $plugin_keyword
                                    ) == true && Demo_Awesome_Admin::is_plugin_activated(
                                        $plugin_keyword
                                    ) == false) {
                                    $required_plugin = sprintf(
                                        '<span class="badge badge-error mr-1"><span class="mr-1">%1$s</span>%2$s</span>',
                                        Demo_Awesome_Admin::get_svg('error'),
                                        esc_html__('Not active', 'demo-awesome')
                                    );
                                    $premium_plugin = sprintf(
                                        '<a class="button button-proceed" target="_blank" href="'.get_admin_url(
                                        ).'plugins.php?s=%s">%s</a>',
                                        esc_attr($plugin_keyword),
                                        esc_html__('Activate', 'demo-awesome')
                                    );
                                    $show_required_description = true;
                                } else {
                                    $required_plugin = sprintf(
                                        '<span class="badge badge-error mr-1"><span class="mr-1">%1$s</span>%2$s</span>',
                                        Demo_Awesome_Admin::get_svg('error'),
                                        esc_html__('Not installed', 'demo-awesome')
                                    );
                                    if (isset($plugin['follow_download']) && $plugin['follow_download']) {
                                        $premium_plugin = sprintf(
                                            '<div class="badge badge-premium">%s</div>',
                                            $plugin['disable_description']
                                        );
                                     //   var_dump($plugin);
	                                    $plugin['slug'] =  isset( $plugin['slug']{1} ) ? $plugin['slug'] : $plugin['keyword'];
                                        $premium_plugin = sprintf(
                                            '<a class="button evole-install-plugin button-proceed" target="_blank"
                                 data-plugin="'.$plugin['slug'].'" href="'.self_admin_url('#%s">%s</a>'),
                                            $plugin['keyword'],
                                            esc_html__('Install', 'demo-awesome')
                                        );
                                    } else {
                                        $premium_plugin = sprintf(
                                            '<a class="button button-proceed" target="_blank" href="'.self_admin_url(
                                                'plugin-install.php?tab=plugin-information&amp;plugin=%s">%s</a>'
                                            ),
                                            $plugin['keyword'],
                                            esc_html__('Install', 'demo-awesome')
                                        );
                                    }
                                    $show_required_description = true;
                                }

                                ?>
                                <li>
                                    <strong><?php
                                        echo wp_kses_post($plugin['name'].'<br />'.$required_plugin); ?></strong>
                                    <?php if (current_user_can(  'install_plugins' ) || current_user_can( 'update_plugins' )) {
                                        echo wp_kses_post($premium_plugin);
                                    } ?>
                                </li>
                                <?php
                            }
                        } ?>
                    </ul>
                    <?php if ($show_required_description) { ?>
                        <p class="alert alert-info required-description-text"><span
                                    class="mr-1"><?php echo wp_kses_post( Demo_Awesome_Admin::get_svg('info')); ?></span><?php esc_html_e(
                                'You can install/activate the required plugin(s) before import or import the demo content now. Importing content without enabled required plugin(s) may result in broken page layout or not importing the data supported by the plugin(s).',
                                'demo-awesome'
                            ); ?>
                        </p>
                    <?php }
                } ?>
            </div>
        </div>

        <?php


    }
}
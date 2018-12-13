<?php

$list_demos = array(
	'app' => 
	array(
		'name' => 'App',
		'items' => array(
			array(
				'name' => 'App 1',
				'preview_url' => 'https://demo.theme4press.com',
				'require_ver' => '2.9',
				'plugins' => array(
					'WooCommerce' => array(
						'name' => 'WooCommerce',
						'keyword' => 'WooCommerce',
						'enable_description' => '',
						'disable_description' => '',
					),
					'Theme4Press_Shortcodes' => array(
						'name' => 'Theme4Press Shortcodes',
						'keyword' => 'Theme4Press Shortcodes',
						'enable_description' => '',
						'disable_description' => '',
					),
					'RevSlider' => array(
						'name' => 'Revolution Slider',
						'keyword' => 'Revolution Slider',
						'follow_download' => true,
						'enable_description' => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),		
				),
				'folder_path' => 'blog/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						array(
							'primary-menu' => 'Main menu',
							'sticky_navigation' => 'Main menu',
						)
					)
				)
			),
			array(
				'name' => 'App 2',
				'preview_url' => 'https://demo.theme4press.com',
				'require_ver' => '2.9',
				'plugins' => array(
					'WooCommerce',
					'Theme4Press Shortcodes',
					'Revolution Slider',			
				),
			),
			array(
				'name' => 'App 3',
				'require_ver' => '2.9',
			),
		),
	),
	'business' => array(
		'name' => 'Business',
		'items' => array(
			array(
				'name' => 'Business 1',
				'preview_url' => 'https://demo.theme4press.com',
				'require_ver' => '2.5',
				'plugins' => array(
					'WooCommerce',
					'Theme4Press Shortcodes',
					'Revolution Slider',			
				),
			),
			array(
				'name' => 'Business 2',
				'preview_url' => 'https://demo.theme4press.com',
				'require_ver' => '2.9',
				'plugins' => array(
					'WooCommerce',
					'Theme4Press Shortcodes',
					'Revolution Slider',			
				),
			),
			array(
				'name' => 'Business 1',
				'preview_url' => 'https://demo.theme4press.com',
				'require_ver' => '3.9',
				'plugins' => array(
					'WooCommerce 3',
					'Theme4Press Shortcodes 3',
					'Revolution Slider 3',			
				),
			),
		),
	),
);
header('Content-Type: application/json');
echo json_encode($list_demos);

$myfile = fopen("get-list-demos.json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($list_demos));
fclose($myfile);
exit;
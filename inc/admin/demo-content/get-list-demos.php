<?php
error_reporting( - 1 );
ini_set( 'display_errors', 'On' );

$list_demos = array(
	'app'         =>
		array(
			'name'  => 'App',
			'items' => array(
				array(
					'name'                   => 'App 1',
					'preview_url'            => 'https://demo.theme4press.com/evolve-app/',
					'require_ver_premium'    => '2.9.1',
					'premium_demo'           => true,
					'required_plugins'       => true,
					'plugins'                => array(
						'Component Shortcodes' => array(
							'name'                => 'Component Shortcodes',
							'keyword'             => 'Component Shortcodes',
							'follow_download'     => true,
							'enable_description'  => '',
							'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
						),
					),
					'folder_path'            => 'app/1',
					'customizer_data_update' => array(
						'nav_menu_locations' => array(
							'primary-menu'      => 'Main Menu',
							'sticky_navigation' => 'Main Menu',
						)
					),
					'option_update'         => array(
						'blogname' 			=> 'BRAND NEW APP',
						'blogdescription' 	=> 'Nullam semper leo a tortor mattis',
						'show_on_front' 	=> 'page',
						'update_pages'  	=> array(
							'page_on_front' => 'Home',
						)
					)
				),
			),
		),
	'blog'        => array(
		'name'  => 'Blog',
		'items' => array(
			array(
				'name'                   => 'Blog 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-blog/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'Slider Revolution' => array(
						'name'                => 'Slider Revolution',
						'keyword'             => 'Slider Revolution',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
				),
				'folder_path'            => 'blog/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'show_on_front'  => 'page',
					'update_pages'   => array(
						'page_for_posts' => 'Home',
					),
					'posts_per_page' => 9
				)
			),
		),
	),
	'business'    => array(
		'name'  => 'Business',
		'items' => array(
			array(
				'name'                   => 'Business 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-default/',
				'require_ver_free'       => '4.0.2',
				'require_ver_premium'    => '2.9.1',
				'folder_path'            => 'business/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'posts_per_page' => 3
				),
				'update_galleries'       => array(
					'pages' => array(
						array(
							'title' => 'Our Team',
							'items' => array(
								array(
									'ids'   => '11,10',
									'names' => 'testimonial-2,testimonial-1'
								)
							)
						)
					),
				)
			),
			array(
				'name'                   => 'Business 2',
				'preview_url'            => 'https://demo.theme4press.com/evolve-hot-spa/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'Component Shortcodes' => array(
						'name'                => 'Component Shortcodes',
						'keyword'             => 'Component Shortcodes',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
				),
				'folder_path'            => 'business/2',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'show_on_front' => 'page',
					'update_pages'  => array(
						'page_on_front' => 'Home',
					)
				),
			),
			array(
				'name'                   => 'Business 3',
				'preview_url'            => 'https://demo.theme4press.com/evolve-plus-default/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'Component Shortcodes' => array(
						'name'                => 'Component Shortcodes',
						'keyword'             => 'Component Shortcodes',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
					'WooCommerce'          => array(
						'name'                => 'WooCommerce',
						'keyword'             => 'WooCommerce',
						'enable_description'  => '',
						'disable_description' => '',
					),
				),
				'folder_path'            => 'business/3',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'show_on_front'  => 'page',
					'update_pages'   => array(
						'page_on_front'            => 'Home',
						'page_for_posts'           => 'Blog',
						'woocommerce_shop_page_id' => 'Shop'
					),
					'posts_per_page' => 9
				),
			),
			array(
				'name'                   => 'Business 4',
				'preview_url'            => 'https://demo.theme4press.com/evolve-the-office/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'Component Shortcodes' => array(
						'name'                => 'Component Shortcodes',
						'keyword'             => 'Component Shortcodes',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
					'LayerSlider WP'          => array(
						'name'                => 'LayerSlider',
						'keyword'             => 'LayerSlider',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
				),
				'folder_path'            => 'business/4',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'show_on_front' => 'page',
					'update_pages'  => array(
						'page_on_front' => 'Home',
					)
				),
			),
		),
	),
	'eshop'       => array(
		'name'  => 'eShop',
		'items' => array(
			array(
				'name'                   => 'eShop 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-eshop/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'WooCommerce' => array(
						'name'                => 'WooCommerce',
						'keyword'             => 'WooCommerce',
						'enable_description'  => '',
						'disable_description' => '',
					),
				),
				'folder_path'            => 'eshop/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'show_on_front' => 'page',
					'update_pages'  => array(
						'page_on_front'            => 'Shop',
						'woocommerce_shop_page_id' => 'Shop'
					)
				)
			),
			array(
				'name'                   => 'eShop 2',
				'preview_url'            => 'https://demo.theme4press.com/evolve-my-store/',
				'require_ver_free'       => '4.0.2',
				'require_ver_premium'    => '2.9.1',
				'required_plugins'       => true,
				'plugins'                => array(
					'WooCommerce' => array(
						'name'                => 'WooCommerce',
						'keyword'             => 'WooCommerce',
						'enable_description'  => '',
						'disable_description' => '',
					),
				),
				'folder_path'            => 'eshop/2',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				)
			),
		),
	),
	'magazine'    => array(
		'name'  => 'Magazine',
		'items' => array(
			array(
				'name'                   => 'Magazine 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-magazine/',
				'require_ver_free'       => '4.0.2',
				'require_ver_premium'    => '2.9.1',
				'folder_path'            => 'magazine/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				)
			),
		),
	),
	'personal'    => array(
		'name'  => 'Personal',
		'items' => array(
			array(
				'name'                   => 'Personal 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-profile/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'Component Shortcodes' => array(
						'name'                => 'Component Shortcodes',
						'keyword'             => 'Component Shortcodes',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
				),
				'folder_path'            => 'personal/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu'      => 'Main menu',
						'sticky_navigation' => 'Main menu',
					)
				),
				'option_update'          => array(
					'show_on_front' => 'page',
					'update_pages'  => array(
						'page_on_front' => 'Home',
					)
				)
			),
		),
	),
	'photography' => array(
		'name'  => 'Photography',
		'items' => array(
			array(
				'name'                   => 'Photography 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-photography/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'required_plugins'       => true,
				'plugins'                => array(
					'Slider Revolution' => array(
						'name'                => 'Slider Revolution',
						'keyword'             => 'Slider Revolution',
						'follow_download'     => true,
						'enable_description'  => '',
						'disable_description' => 'Premium plugin - please visit your Theme4Press account to download it',
					),
				),
				'folder_path'            => 'photography/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu' => 'Main menu'
					)
				),
				'option_update'          => array(
					'show_on_front' => 'page',
					'update_pages'  => array(
						'page_on_front' => 'Home'
					)
				),
			),
		),
	),
	'portfolio'   => array(
		'name'  => 'Portfolio',
		'items' => array(
			array(
				'name'                   => 'Portfolio 1',
				'preview_url'            => 'https://demo.theme4press.com/evolve-portfolio/',
				'require_ver_premium'    => '2.9.1',
				'premium_demo'           => true,
				'folder_path'            => 'portfolio/1',
				'customizer_data_update' => array(
					'nav_menu_locations' => array(
						'primary-menu' => 'Main menu'
					)
				),
				'option_update'          => array(
					'show_on_front' => 'page',
					'update_pages'  => array(
						'page_on_front' => 'Home',
					)
				)
			),
		),
	),
);
header( 'Content-Type: application/json' );
echo json_encode( $list_demos );

$myfile = fopen( "get-list-demos.json", "w" ) or die( "Unable to open file!" );
fwrite( $myfile, json_encode( $list_demos ) );
fclose( $myfile );
exit;
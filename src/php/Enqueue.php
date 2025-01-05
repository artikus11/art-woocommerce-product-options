<?php

namespace Art\WoocommerceProductOptions;

class Enqueue {

	/**
	 * @var \Art\WoocommerceProductOptions\Main|null
	 */
	protected ?Main $main;


	public function __construct( Main $main ) {

		$this->main = $main;
	}


	public function init_hooks(): void {

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 100 );
	}


	public function admin_scripts(): void {

		$current_screen = get_current_screen();

		if ( 'product' === $current_screen->id && 'product' === $current_screen->post_type ) {
			wp_enqueue_script(
				'awpo-admin-script',
				$this->main->utils->get_plugin_url() . '/assets/js/admin-script.js',
				[
					'jquery',
					'jquery-ui-widget',
					'wp-util',
					'wc-product-editor',
				],
				$this->main->utils->get_plugin_version(),
				true
			);

			wp_enqueue_style(
				'awpo-admin-style',
				$this->main->utils->get_plugin_url() . '/assets/css/admin-style.css',
				[],
				$this->main->utils->get_plugin_version(),
			);
		}
	}
}

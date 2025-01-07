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
		add_action( 'wp_enqueue_scripts', [ $this, 'public_scripts' ], 100 );
	}


	public function public_scripts(): void {

		if ( is_product() ) {
			wp_enqueue_script(
				'awpo-public-script',
				$this->main->utils->get_plugin_url() . '/assets/js/public-script.js',
				[
					'jquery',
				],
				$this->main->utils->get_plugin_version(),
				true
			);

			wp_localize_script(
				'awpo-public-script',
				'awpo_scripts_settings',
				[
					'required_text'      => 'Опция обязательна. Пожалуйста выберите нужное значение',
					'price_decimal_sep'  => get_option( 'woocommerce_price_decimal_sep' ), // Десятичный разделитель
					'price_num_decimals' => get_option( 'woocommerce_price_num_decimals' ), // Число дробных знаков
					'price_thousand_sep' => get_option( 'woocommerce_price_thousand_sep' ), // Разделитель тысяч
					'price_currency_pos' => get_option( 'woocommerce_currency_pos' ), // Позиция валюты
					'price'              => $this->main->get_product_price(), // Цена
					'is_sale'            => $this->main->is_product_sale(),
				]
			);
		}
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

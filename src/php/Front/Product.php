<?php

namespace Art\WoocommerceProductOptions\Front;

use Art\WoocommerceProductOptions\Main;

class Product {

	/**
	 * @var \Art\WoocommerceProductOptions\Main
	 */
	protected Main $main;


	public function __construct( Main $main ) {

		$this->main = $main;
	}


	public function init_hooks(): void {

		add_filter( 'woocommerce_before_add_to_cart_button', [ $this, 'display_options' ], 99, 1 );
	}


	public function display_options(): void {

		$options      = $this->main->get_helper()->get_product_options();
		$options_json = $this->get_option_data_json();

		if ( empty( $options ) ) {
			return;
		}

		load_template(
			$this->main->get_template( 'public/options.php' ),
			false,
			[
				'options'      => $options,
				'options_json' => $options_json,
			]
		);
	}


	public function get_option_data_json(): string {

		$data = [];

		$options = $this->main->get_helper()->get_product_options();

		if ( empty( $options ) ) {
			return '';
		}

		foreach ( $options as $key => $option ) {
			$option_id = (int) $key;

			if ( ! empty( $option['price'] ) && 0 !== $option['price'] ) {
				$data['optionPrices'][ $option_id ] = (float) $option['price'];
			}

			foreach ( $option['values'] as $value_key => $value ) {
				$value_id = (int) $value_key;

				if ( ! empty( $value['price'] ) && 0 !== $value['price'] ) {
					$data['valuePrices'][ $value_id ] = (float) $value['price'];
				}
			}
		}

		return $this->main->get_helper()->get_json( $data );
	}
}

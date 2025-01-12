<?php

namespace Art\WoocommerceProductOptions;

class Helper {

	public static function get_product_options(): ?array {

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return null;
		}

		return $product->get_meta( 'awpo_options' ) ? $product->get_meta( 'awpo_options' ) : null;
	}


	public static function get_product_options_by_product_id( $product_id ): ?array {

		if ( ! is_object( $product_id ) ) {
			$product = wc_get_product( $product_id );
		}

		if ( empty( $product ) ) {
			return null;
		}

		return $product->get_meta( 'awpo_options' ) ? $product->get_meta( 'awpo_options' ) : null;
	}


	public static function get_product_price(): ?string {

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return null;
		}

		return $product->get_price();
	}


	public static function is_product_sale(): ?string {

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return null;
		}

		return $product->is_on_sale();
	}


	/**
	 * @param  array $data
	 *
	 * @return string
	 */
	public static function get_json( array $data ): string {

		$options_json = wp_json_encode( $data );

		return function_exists( 'wc_esc_json' )
			? wc_esc_json( $options_json )
			: _wp_specialchars( $options_json, ENT_QUOTES, 'UTF-8', true );
	}
}

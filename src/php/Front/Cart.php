<?php

namespace Art\WoocommerceProductOptions\Front;

use Art\WoocommerceProductOptions\Main;

class Cart {

	/**
	 * @var \Art\WoocommerceProductOptions\Main
	 */
	protected Main $main;


	public function __construct( Main $main ) {

		$this->main = $main;
	}


	public function init_hooks(): void {

		add_action( 'woocommerce_add_cart_item_data', [ $this, 'add_options_to_cart_item' ], 1000, 3 );
		add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'validate_cart_data' ], 10, 2 );

		add_filter( 'woocommerce_get_item_data', [ $this, 'display_fields_on_cart_and_checkout' ], 10, 2 );
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'adjust_cart_item_pricing' ] );

		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'create_order_line_item' ], 20, 3 );
	}


	public function create_order_line_item( $item, $cart_item_key, $values ): void {

		if ( empty( $values['awpo_option'] ) ) {
			return;
		}

		foreach ( $values['awpo_option'] as $field ) {

			if ( ! empty( $field['value'] ) ) {
				$item->add_meta_data( $field['label'], $this->format_price( $field['value'], $field['price'] ) );
			}
		}
	}


	public function add_options_to_cart_item( $cart_item_data, $product_id, $variation_id ) {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST['awpo_option'] ) ) {
			return $cart_item_data;
		}

		$product = wc_get_product( empty( $variation_id ) ? $product_id : $variation_id );

		$post_data = map_deep( wp_unslash( (array) $_POST['awpo_option'] ), 'sanitize_text_field' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$cart_item_data['awpo_option'] = $this->prepared_selected_data( $product->get_id(), $post_data );

		return $cart_item_data;
	}


	public function validate_cart_data( $passed, $product_id ) {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_POST['awpo_option'] ) && ! isset( $_GET['add-to-cart'] ) ) {
			return $passed;
		}

		$current_options = map_deep( wp_unslash( (array) $_POST['awpo_option'] ), 'sanitize_text_field' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$options = $this->main->get_helper()->get_product_options_by_product_id( $product_id );

		if ( empty( $options ) ) {
			return $passed;
		}

		foreach ( $options as $key => $option ) {
			$option_id = (int) $key;

			if ( ! isset( $option['required'] ) ) {
				continue;
			}

			$required = (bool) $option['required'];

			if ( $required && ( is_array( $current_options[ $option_id ] ) && ! array_filter( $current_options[ $option_id ] ) ) ) {
				$passed = false;

				wc_add_notice(
					sprintf(
						'Опция "%s" является обязательной. Пожалуйста, укажите нужное значение.',
						esc_html( $option['title'] )
					),
					'error'
				);
			}
		}

		return $passed;
	}


	public function display_fields_on_cart_and_checkout( $item_data, $cart_item ) {

		if ( empty( $cart_item['awpo_option'] ) || ! is_array( $cart_item['awpo_option'] ) ) {
			return $item_data;
		}

		if ( ! is_array( $item_data ) ) {
			$item_data = [];
		}

		foreach ( $cart_item['awpo_option'] as $field ) {
			if ( empty( $field['value'] ) ) {
				continue;
			}

			$item_data[] = [
				'key'   => $this->format_price( $field['label'], $field['price'] ),
				'value' => $field['value'],
			];
		}

		return $item_data;
	}


	public function adjust_cart_item_pricing( $cart_obj ): void {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		foreach ( $cart_obj->get_cart() as $item ) {

			if ( empty( $item['awpo_option'] ) ) {
				continue;
			}

			$product       = wc_get_product( $item['variation_id'] ? : $item['product_id'] );
			$base          = (float) $product->get_price();
			$options_total = 0;

			foreach ( $item['awpo_option'] as $field ) {
				if ( ! empty( $field['price'] ) ) {
					$options_total += $field['price'];
				}
			}

			if ( $options_total > 0 ) {
				$item['data']->set_price( $base + $options_total );
			}
		}
	}


	public function format_price( $text, $price ): string {

		$price = empty( $price ) ? '' : sprintf( ' +%s', wc_price( $price ) );

		return sprintf( '%s %s', $text, $price );
	}


	public function prepared_selected_data( $product_id, $selected_values ): array { // phpcs:ignore Generic.Metrics.NestingLevel.MaxExceeded

		$options = $this->main->get_helper()->get_product_options_by_product_id( $product_id );

		if ( empty( $options ) ) {
			return [];
		}

		$formatted_values = [];

		foreach ( $options as $option_id => $option ) {
			if ( empty( $selected_values[ $option_id ] ) ) {
				continue;
			}

			$selected_value = $selected_values[ $option_id ];

			$value = '';
			$price = 0;

			switch ( $option['type'] ) {
				case 'radio':
				case 'drop_down':
					if ( is_array( $selected_value ) ) {
						break;
					}

					$value_id = (int) $selected_value;

					if ( ! isset( $option['values'][ $value_id ] ) ) {
						break;
					}

					$value = $option['values'][ $value_id ]['title'];
					$price = $option['values'][ $value_id ]['price'];

					break;
				case 'checkbox':
				case 'multiple':
					foreach ( (array) $selected_value as $value_id ) {
						if ( ! isset( $option['values'][ $value_id ] ) ) {
							continue;
						}

						$value .= ( '' !== $value ? ', ' : '' ) . $option['values'][ $value_id ]['title'];
						$price += (float) $option['values'][ $value_id ]['price'];
					}
					break;
				case 'field':
				case 'area':
					if ( is_array( $selected_value ) ) {
						break;
					}

					$value = $selected_value;
					break;
			}

			if ( $value ) {
				$formatted_values[] = [
					'label' => $option['title'],
					'value' => $value,
					'price' => $price,
				];
			}
		}

		return $formatted_values;
	}
}

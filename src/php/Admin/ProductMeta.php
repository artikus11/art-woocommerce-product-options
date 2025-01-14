<?php

namespace Art\WoocommerceProductOptions\Admin;

use Art\WoocommerceProductOptions\Main;

class ProductMeta {

	/**
	 * @var \Art\WoocommerceProductOptions\Main
	 */
	protected Main $main;


	public function __construct( Main $main ) {

		$this->main = $main;
	}


	public function init_hooks(): void {

		add_filter( 'woocommerce_product_data_tabs', [ $this, 'add_product_tab' ], 80, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'add_tab_fields' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_options' ] );
	}


	public function add_product_tab( $tabs ) {

		$tabs['awpo_product_options'] = [
			'label'    => 'Дополнительные опции',
			'target'   => 'awpo_product_options_tab',
			'class'    => [],
			'priority' => 90,
		];

		return $tabs;
	}


	public function add_tab_fields(): void {

		$options_json = $this->get_option_data_json();

		echo '<div id="awpo_product_options_tab" class="panel awpo-options-panel-tab hidden">';

		load_template(
			$this->main->get_template( 'admin/option-tab.php' ),
			false,
			[
				'options_json' => $options_json,
			]
		);

		echo '</div>';
	}


	public function save_options( $post_id ): void {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST['awpo_options'] ) ) {
			$_POST['awpo_options'] = [];
		}

		$product = wc_get_product( $post_id );

		$post_data = map_deep( wp_unslash( (array) $_POST['awpo_options'] ), 'sanitize_text_field' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$product->update_meta_data( 'awpo_options', $post_data );
		$product->save();
	}


	public function get_option_data_json(): string {

		$data = [];

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return '';
		}

		$options = $product->get_meta( 'awpo_options' );

		if ( empty( $options ) ) {
			return '';
		}

		$last_option_id        = 0;
		$last_sort_order       = 0;
		$last_value_id         = 0;
		$last_value_sort_order = [];

		foreach ( $options as $key => $option ) {
			$option_id = (int) $key;

			$data['options_data'][ $option_id ] = [
				'option_id'  => $option_id,
				'title'      => $option['title'],
				'type'       => $option['type'],
				'required'   => ! empty( $option['required'] ) ? (int) $option['required'] : 0,
				'sort_order' => (int) $option['sort_order'],
			];

			$last_value_sort_order[ $option_id ] = 0;

			foreach ( $option['values'] as $value_key => $value ) {
				$value_id = (int) $value_key;

				$data['options_data'][ $option_id ]['values'][] = [
					'value_id'   => $value_id,
					'title'      => $value['title'],
					'price'      => (float) $value['price'],
					'sort_order' => (int) $value['sort_order'],
				];

				if ( $value_id > $last_value_id ) {
					$last_value_id = $value_id;
				}

				if ( $value['sort_order'] > $last_value_sort_order[ $option_id ] ) {
					$last_value_sort_order[ $option_id ] = (int) $value['sort_order'];
				}
			}

			$data['option_ids'][] = $option_id;

			if ( $option_id > $last_option_id ) {
				$last_option_id = $option_id;
			}
			if ( $option['sort_order'] > $last_sort_order ) {
				$last_sort_order = (int) $option['sort_order'];
			}
		}

		$data['lastOptionId']       = $last_option_id;
		$data['lastSortOrder']      = $last_sort_order;
		$data['lastValueId']        = $last_value_id;
		$data['lastValueSortOrder'] = $last_value_sort_order;

		return $this->main->get_helper()->get_json( $data );
	}
}

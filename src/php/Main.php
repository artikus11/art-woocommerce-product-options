<?php

namespace Art\WoocommerceProductOptions;

use Art\WoocommerceProductOptions\Admin\ProductMeta;
use Art\WoocommerceProductOptions\Front\Cart;
use Art\WoocommerceProductOptions\Front\Product;

class Main {

	/**
	 * @var \Art\WoocommerceProductOptions\Main|null
	 */
	protected static ?Main $instance = null;


	/**
	 * @var \Art\WoocommerceProductOptions\Utils
	 */
	public Utils $utils;


	/**
	 * @var \Art\WoocommerceProductOptions\Templater
	 */
	protected Templater $templater;


	/**
	 * @var \Art\WoocommerceProductOptions\Public\Cart
	 */
	protected Cart $cart;


	/**
	 * @var \Art\WoocommerceProductOptions\Public\Product
	 */
	protected Product $product;


	/**
	 * @var \Art\WoocommerceProductOptions\Helper
	 */
	protected Helper $helper;


	protected function __construct() {

		$this->init_classes();
		$this->init_hooks();
	}


	public function init_classes(): void {

		$this->utils     = new Utils();
		$this->templater = new Templater();
		$this->helper    = new Helper();

		( new Enqueue( $this ) )->init_hooks();
		( new ProductMeta( $this ) )->init_hooks();

		$this->product = new Product( $this );
		$this->product->init_hooks();

		$this->cart = new Cart( $this );
		$this->cart->init_hooks();
	}


	public function init_hooks(): void {

		add_action( 'before_woocommerce_init', static function () {

			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					Utils::get_plugin_basename(),
					true
				);
			}
		} );
	}


	public static function instance(): Main {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;
	}


	/**
	 * @return \Art\WoocommerceProductOptions\Templater
	 */
	public function get_templater(): Templater {

		return $this->templater;
	}


	/**
	 * @param  string $template_name
	 *
	 * @return string
	 */
	public function get_template( string $template_name ): string {

		return $this->get_templater()->get_template( $template_name );
	}


	/**
	 * @return \Art\WoocommerceProductOptions\Public\Cart
	 */
	public function get_cart(): Cart {

		return $this->cart;
	}


	/**
	 * @return \Art\WoocommerceProductOptions\Public\Product
	 */
	public function get_product(): Product {

		return $this->product;
	}


	/**
	 * @return \Art\WoocommerceProductOptions\Helper
	 */
	public function get_helper(): Helper {

		return $this->helper;
	}
}

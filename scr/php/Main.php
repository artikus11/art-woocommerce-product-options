<?php

namespace Art\WoocommerceProductOptions;

class Main {

	/**
	 * @var \Art\WoocommerceProductOptions\Main|null
	 */
	protected static ?Main $instance = null;


	/**
	 * @var \Art\WoocommerceProductOptions\Utils
	 */
	protected Utils $utils;


	public function init(): void {

		add_action( 'plugins_loaded', [ $this, 'init_all' ], - PHP_INT_MAX );
	}


	public function init_all(): void {

		$this->init_classes();
		$this->init_hooks();
	}


	public function init_classes(): void {

		$this->utils = new Utils();
	}


	public function init_hooks(): void {

		add_action( 'before_woocommerce_init', static function () {

			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					ACL_PLUGIN_FILE,
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
}

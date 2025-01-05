<?php

namespace Art\WoocommerceProductOptions;

use Art\WoocommerceProductOptions\Admin\Admin;

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


	protected function __construct() {

		$this->init_classes();
		$this->init_hooks();
	}


	public function init_classes(): void {

		$this->utils     = new Utils();
		$this->templater = new Templater();

		( new Enqueue( $this ) )->init_hooks();

		( new Admin( $this ) )->init_hooks();
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
}

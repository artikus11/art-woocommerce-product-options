<?php
/**
 * Plugin Name: Art WooCommerce Product Options
 * Plugin URI: wpruse.ru
 * Text Domain: art-woocommerce-product-options
 * Domain Path: /languages
 * Description: Плагин для WooCommerce. добавляет дополнтельные опции к товару
 * Version: 1.1.2
 * Author: Artem Abramovich
 * Author URI: https://wpruse.ru/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 5.0
 *
 * Requires PHP: 8.0
 * Requires WP: 6.0
 *
 * Requires Plugins: woocommerce
 *
 * Copyright Artem Abramovich
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const AWPO_PLUGIN_DIR    = __DIR__;
const AWPO_PLUGIN_AFILE  = __FILE__;
const AWPO_PLUGIN_VER    = '1.1.2';
const AWPO_PLUGIN_NAME   = 'Art WooCommerce Product Options';
const AWPO_PLUGIN_SLUG   = 'art-woocommerce-product-options';
const AWPO_PLUGIN_PREFIX = 'awpo';

define( 'AWPO_PLUGIN_URI', untrailingslashit( plugin_dir_url( AWPO_PLUGIN_AFILE ) ) );
define( 'AWPO_PLUGIN_FILE', plugin_basename( __FILE__ ) );

require AWPO_PLUGIN_DIR . '/vendor/autoload.php';

if ( ! function_exists( 'awpo' ) ) {
	/**
	 * @return object Main class object.
	 * @since 1.0.0
	 */
	function awpo(): object {

		return \Art\WoocommerceProductOptions\Main::instance();
	}
}

awpo();

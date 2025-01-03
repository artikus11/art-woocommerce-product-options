<?php
/**
 * Plugin Name: Art WooCommerce Product Options
 * Plugin URI: wpruse.ru
 * Text Domain: art-woocommerce-product-options
 * Domain Path: /languages
 * Description: Длагин для WooCommerce. добавляет дополнтельные опции к товару
 * Version: 1.0.0
 * Author: Artem Abramovich
 * Author URI: https://wpruse.ru/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 5.0
 *
 * Requires PHP: 7.4
 * Requires WP:5.5
 *
 * Copyright Artem Abramovich
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


const AWPO_PLUGIN_DIR   = __DIR__;
const AWPO_PLUGIN_AFILE = __FILE__;
const AWPO_PLUGIN_VER   = '1.0.0';
const AWPO_PLUGIN_NAME  = 'Art WooCommerce Product Options';
const AWPO_PLUGIN_SLUG  = 'art-woocommerce-product-options';
const AWPO_PLUGIN_PREFIX  = 'awpo';

define( 'AWPO_PLUGIN_URI', untrailingslashit( plugin_dir_url( AWPO_PLUGIN_AFILE ) ) );
define( 'AWPO_PLUGIN_FILE', plugin_basename( __FILE__ ) );

require AWPO_PLUGIN_DIR . '/vendor/autoload.php';
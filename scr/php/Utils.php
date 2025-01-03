<?php
/**
 * Class Utils
 *
 * @see     https://wpruse.ru/my-plugins/art-woocommerce-product-option/
 * @package art-woocommerce-product-option/src/php
 * @version 1.0.0
 */

namespace Art\WoocommerceProductOptions;

/**
 * Class Utils
 */
class Utils {

	/**
	 * Get plugin path.
	 *
	 * @return string
	 */
	public static function get_plugin_path(): string {

		return constant( 'AWPO_PLUGIN_DIR' );
	}


	/**
	 * Get plugin version.
	 *
	 * @return string
	 */
	public static function get_plugin_version(): string {

		return constant( 'AWPO_PLUGIN_VER' );
	}


	/**
	 * Get plugin URL.
	 *
	 * @return string
	 */
	public static function get_plugin_url(): string {

		return constant( 'AWPO_PLUGIN_URI' );
	}


	/**
	 * Get plugin slug.
	 *
	 * @return string
	 */
	public static function get_plugin_slug(): string {

		return constant( 'AWPO_PLUGIN_SLUG' );
	}


	/**
	 * Get plugin file.
	 *
	 * @return string
	 */
	public static function get_plugin_file(): string {

		return constant( 'AWPO_PLUGIN_AFILE' );
	}


	/**
	 * Get plugin base name.
	 *
	 * @return string
	 */
	public static function get_plugin_basename(): string {

		return plugin_basename( AWPO_PLUGIN_FILE );
	}


	/**
	 * Get plugin title.
	 *
	 * @return string
	 */
	public static function get_plugin_title(): string {

		return constant( 'AWPO_PLUGIN_NAME' );
	}


	/**
	 * Get plugin prefix.
	 *
	 * @return string
	 */
	public static function get_plugin_prefix(): string {

		return constant( 'AWPO_PLUGIN_PREFIX' );
	}
}

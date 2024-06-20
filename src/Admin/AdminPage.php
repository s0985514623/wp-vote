<?php
/**
 * Admin Page Settings
 */

declare(strict_types=1);

namespace J7\WpPlugin\Admin;

use J7\WpPlugin\Plugin;
use J7\WpPlugin\Classes\Example;
/**
 * Admin Page
 */
final class AdminPage extends Example {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		\add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ), 99 );
	}
	/**
	 * Admin Enqueue script
	 * You can load the script on demand
	 *
	 * @param string $hook current page hook
	 *
	 * @return void
	 */
	public function admin_enqueue_script( $hook ): void {
		\wp_enqueue_script(
			Plugin::$kebab,
			Plugin::$url . '/js/dist/index-admin.js',
			array( 'jquery' ),
			Plugin::$version,
			array(
				'in_footer' => true,
				'strategy'  => 'async',
			)
		);

		\wp_localize_script(
			Plugin::$kebab,
			Plugin::$snake . '_data',
			array(
				'Example:' => $this->example,
			)
		);

		\wp_enqueue_style(
			Plugin::$kebab,
			Plugin::$url . '/js/dist/assets/css/index.css',
			array(),
			Plugin::$version
		);
	}
}

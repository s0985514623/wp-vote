<?php
/**
 * Admin Page Settings
 */

declare(strict_types=1);

namespace J7\WpPlugin\FrontEnd;

use J7\WpPlugin\Plugin;
/**
 * Admin Page
 */
final class FrontEndPage {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		\add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue_script' ), 99 );
	}
	/**
	 * Front-end Enqueue script
	 * You can load the script on demand
	 *
	 * @return void
	 */
	public function frontend_enqueue_script(): void {
		\wp_enqueue_script(
			Plugin::$kebab,
			Plugin::$url . '/js/dist/index-frontend.js',
			array( 'jquery' ),
			Plugin::$version,
			array(
				'in_footer' => true,
				'strategy'  => 'async',
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

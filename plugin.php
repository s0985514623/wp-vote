<?php
/**
 * Plugin Name:       WP Vote | 民調投票系統
 * Plugin URI:        https://github.com/s0985514623
 * Description:       民調投票系統
 * Version:           1.0.1
 * Requires at least: 5.7
 * Requires PHP:      8.
 * Author:            s0985514623
 * Author URI:        https://github.com/s0985514623
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp_vote
 * Domain Path:       /languages
 * Tags:
 */

declare (strict_types = 1);

namespace Ren\WpVote;

use Ren\WpVote\Bootstrap;

if ( ! \class_exists( 'Ren\WpVote\Plugin' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';

	/**
		* Class Plugin
		*/
	final class Plugin {
		use \J7\WpUtils\Traits\PluginTrait;
		use \J7\WpUtils\Traits\SingletonTrait;

		/**
		 * Constructor
		 */
		public function __construct() {
			// require_once __DIR__ . '/inc/class/class-bootstrap.php';

			// $this->required_plugins = array(
			// array(
			// 'name'     => 'WooCommerce',
			// 'slug'     => 'woocommerce',
			// 'required' => true,
			// 'version'  => '7.6.0',
			// ),
			// array(
			// 'name'     => 'WP Toolkit',
			// 'slug'     => 'wp-toolkit',
			// 'source'   => 'Author URL/wp-toolkit/releases/latest/download/wp-toolkit.zip',
			// 'required' => true,
			// ),
			// );
			$this->init(
				array(
					'app_name'    => 'WP Vote',
					'github_repo' => 'https://github.com/s0985514623/wp-vote',
					'callback'    => array( Bootstrap::class, 'instance' ),
				)
			);
		}
	}

	Plugin::instance();
}

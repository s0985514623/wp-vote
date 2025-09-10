<?php
/**
 * Bootstrap
 */

declare (strict_types = 1);

namespace Ren\WpVote;

use Error;
use Ren\WpVote\Utils\Base;
use Ren\WpVote\Classes\CPT;
use Ren\WpVote\Classes\ACF;
use Ren\WpVote\Classes\Template;
use Ren\WpVote\Classes\Form;
/**
 * Class Bootstrap
 */
final class Bootstrap {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
        CPT::instance();
        ACF::instance();
        Template::instance();
        Form::instance();
		\add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_script' ], 99 );
		\add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue_script' ], 99 );
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
		$this->enqueue_script();
	}


	/**
	 * Front-end Enqueue script
	 * You can load the script on demand
	 *
	 * @return void
	 */
	public function frontend_enqueue_script(): void {
		$this->enqueue_script();
	}

	/**
	 * Enqueue script
	 * You can load the script on demand
	 *
	 * @return void
	 */
	public function enqueue_script(): void {

	}
}
<?php
/**
 * Bootstrap
 */

declare (strict_types = 1);

namespace J7\WpPlugin;

use J7\WpPlugin\Admin\AdminPage;
use J7\WpPlugin\FrontEnd\FrontEndPage;
/**
 * Class Bootstrap
 */
final class Bootstrap {
	use \J7\WpUtils\Traits\SingletonTrait;


	/**
	 * Constructor
	 */
	public function __construct() {
		\add_action( 'init', array( $this, 'init' ) );
	}
	/**
	 * Init
	 */
	public function init(): void {
		if ( is_admin() ) {
			// 執行後台專用代碼
			AdminPage::instance();
		} else {
			// 執行前台專用代碼
			FrontEndPage::instance();
		}
	}
}

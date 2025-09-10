<?php
    /**
     * Form Class
     * 增加短碼
     * AJAX 提交處理
     */

    namespace Ren\WpVote\Classes;

    class Form
    {
        use \J7\WpUtils\Traits\SingletonTrait;

        public function __construct()
        {
            add_shortcode('vote_form', [$this, 'vote_form_shortcode']);
        }

        public function vote_form_shortcode()
        {
            //ob_start 應用
            $html = '';
            ob_start();
            ?>

            <?php
            $html .= ob_get_clean();
            return $html;
        }
}

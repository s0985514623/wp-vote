<?php
/**
 * Add Template Vote Page
 */

declare (strict_types = 1);

namespace Ren\WpVote\Classes;

use Ren\WpVote\Plugin;

/**
 * Class Template
 */
final class Template
{
    use \J7\WpUtils\Traits\SingletonTrait;

    public function __construct()
    {
        \add_action('init', [$this, 'init']);
    }

    public function init()
    {
        // 讓後台「版面模板」清單出現外掛模板
        add_filter('theme_page_templates', function (array $templates) {
            // key 是儲存在 _wp_page_template 的檔名字串（你自定即可）
            $templates['archive-vote.php'] = '民調頁面';
            return $templates;
        });

        // 當該模板被使用時，改用外掛內的檔案
        add_filter('template_include', function ($template) {
            if (is_page()) {
                $slug = get_page_template_slug(); // 例如 'archive-vote.php'
                if ($slug === 'archive-vote.php') {
                    $file = dirname(__DIR__) . '/Template/archive-vote.php';
                    if (file_exists($file)) {
                        return $file;
                    }
                }
            }
            return $template;
        });

        /**
         * 單篇樣板載入器：
         * 1) 若是單篇 vote，就先讓主題有機會覆蓋 (child/parent theme)
         * 2) 若主題沒有覆蓋，回退到外掛內的樣板
         */
        add_filter('single_template', function ($single) {
            if (is_singular('vote')) {
                // 允許主題覆蓋：主題根目錄找 single-vote.php 或 wp-vote/single-vote.php
                $theme_template = locate_template(['single-vote.php', 'wp-vote/single-vote.php']);
                if ($theme_template) {
                    return $theme_template;
                }

                // 回退到外掛內的樣板
                $plugin_template = dirname(__DIR__) . '/Template/single-vote.php';
                if (file_exists($plugin_template)) {
                    return $plugin_template;
                }
            }

            return $single;
        }, 10);

    }
}

<?php
/**
 * Custom Post Type: Wp Vote
 */

declare (strict_types = 1);

namespace Ren\WpVote\Classes;

use Ren\WpVote\Plugin;

/**
 * Class CPT
 */
final class CPT
{
    use \J7\WpUtils\Traits\SingletonTrait;

    /**
     * Post metas
     *
     * @var array
     */
    public $post_meta_array = [];
    /**
     * Rewrite
     *
     * @var array
     */
    public $rewrite = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $args = [
            'post_meta_array' => ['meta', 'settings'],
            'rewrite'         => [
                'template_path' => 'test.php',
                'slug'          => 'test',
                'var'           => Plugin::$snake . '_test',
            ],

        ];
        $this->post_meta_array = $args['post_meta_array'];
        $this->rewrite         = $args['rewrite'] ?? [];

        \add_action('init', [$this, 'init']);

        if (! empty($args['post_meta_array'])) {
            // [ 'meta', 'settings' ]
            \add_action('rest_api_init', [$this, 'add_post_meta']);
        }

        \add_action('load-post.php', [$this, 'init_metabox']);
        \add_action('load-post-new.php', [$this, 'init_metabox']);

        if (! empty($args['rewrite'])) {
            \add_filter('query_vars', [$this, 'add_query_var']);
            \add_filter('template_include', [$this, 'load_custom_template'], 99);
        }
        // error_log('CPT');
    }

    /**
     * Initialize
     */
    public function init(): void
    {
        $this->register_cpt();
        // $this->register_post_meta();

        // add {$this->post_type}/{slug}/test rewrite rule
        if (! empty($this->rewrite)) {
            \add_rewrite_rule('^wp-vote/([^/]+)/' . $this->rewrite['slug'] . '/?$', 'index.php?post_type=wp-vote&name=$matches[1]&' . $this->rewrite['var'] . '=1', 'top');
            \flush_rewrite_rules();
        }
    }

    /**
     * Register wp-vote custom post type
     */
    public function register_cpt(): void
    {

        $labels = [
            'name'                     => \esc_html__('民調', 'wp_vote'),
            'singular_name'            => \esc_html__('民調', 'wp_vote'),
            'add_new'                  => \esc_html__('新增民調', 'wp_vote'),
            'add_new_item'             => \esc_html__('新增民調', 'wp_vote'),
            'edit_item'                => \esc_html__('編輯民調', 'wp_vote'),
            'new_item'                 => \esc_html__('新民調', 'wp_vote'),
            'view_item'                => \esc_html__('查看民調', 'wp_vote'),
            'view_items'               => \esc_html__('查看民調', 'wp_vote'),
            'search_items'             => \esc_html__('搜尋民調', 'wp_vote'),
            'not_found'                => \esc_html__('Not Found 民調', 'wp_vote'),
            'not_found_in_trash'       => \esc_html__('Not found in trash 民調', 'wp_vote'),
            'parent_item_colon'        => \esc_html__('Parent item 民調', 'wp_vote'),
            'all_items'                => \esc_html__('全部民調', 'wp_vote'),
            'archives'                 => \esc_html__('民調', 'wp_vote'),
            'attributes'               => \esc_html__('民調', 'wp_vote'),
            'insert_into_item'         => \esc_html__('Insert to this vote', 'wp_vote'),
            'uploaded_to_this_item'    => \esc_html__('Uploaded to this vote', 'wp_vote'),
            'featured_image'           => \esc_html__('Featured image', 'wp_vote'),
            'set_featured_image'       => \esc_html__('Set featured image', 'wp_vote'),
            'remove_featured_image'    => \esc_html__('Remove featured image', 'wp_vote'),
            'use_featured_image'       => \esc_html__('Use featured image', 'wp_vote'),
            'menu_name'                => \esc_html__('民調列表', 'wp_vote'),
            'filter_items_list'        => \esc_html__('Filter vote list', 'wp_vote'),
            'filter_by_date'           => \esc_html__('Filter by date', 'wp_vote'),
            'items_list_navigation'    => \esc_html__('vote list navigation', 'wp_vote'),
            'items_list'               => \esc_html__('vote list', 'wp_vote'),
            'item_published'           => \esc_html__('vote published', 'wp_vote'),
            'item_published_privately' => \esc_html__('vote published privately', 'wp_vote'),
            'item_reverted_to_draft'   => \esc_html__('vote reverted to draft', 'wp_vote'),
            'item_scheduled'           => \esc_html__('vote scheduled', 'wp_vote'),
            'item_updated'             => \esc_html__('vote updated', 'wp_vote'),
        ];
        $args = [
            'label'                 => \esc_html__('vote', 'wp_vote'),
            'labels'                => $labels,
            'description'           => '',
            'public'                => true,
            'hierarchical'          => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'show_in_menu'          => \WP_DEBUG,
            'show_ui'               => \WP_DEBUG,
            'show_in_nav_menus'     => false,
            'show_in_admin_bar'     => false,
            'show_in_rest'          => true,
            'query_var'             => false,
            'can_export'            => true,
            'delete_with_user'      => true,
            'has_archive'           => false,
            'rest_base'             => '',
            'menu_position'         => 6,
            'menu_icon'             => 'dashicons-store',
            'capability_type'       => 'post',
            'supports'              => ['title', 'editor', 'thumbnail', 'custom-fields', 'author','comments'],
            'taxonomies'            => [],
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'rewrite'               => [
                'with_front' => true,
            ],
        ];
        \register_post_type('vote', $args);

    }

    /**
     * Register meta fields for post type to show in rest api
     */
    public function add_post_meta(): void
    {
        // [ 'meta', 'settings' ]
        foreach ($this->post_meta_array as $meta_key) {
            \register_meta(
                'post',
                Plugin::$snake . '_' . $meta_key,
                [
                    'type'         => 'string',
                    'show_in_rest' => true,
                    'single'       => true,
                ]
            );
        }
    }

    /**
     * Meta box initialization.
     */
    public function init_metabox(): void
    {
        \add_action('add_meta_boxes', [$this, 'add_metabox']);
        \add_action('save_post', [$this, 'save_metabox'], 10, 2);
        \add_filter('rewrite_rules_array', [$this, 'custom_post_type_rewrite_rules']);
    }

    /**
     * Adds the meta box.
     *
     * @param string $post_type Post type.
     */
    public function add_metabox(string $post_type): void
    {
        // Post type array
        if (in_array($post_type, [Plugin::$kebab])) {

            \add_meta_box(
                Plugin::$kebab . '-metabox',
                __('WP Vote', 'wp_vote'),
                [$this, 'render_meta_box'],
                $post_type,
                'advanced',
                'high'
            );
        }
    }

    /**
     * Render meta box to input.
     *
     * @param \WP_Post $post Post.
     * @param array    $args Args.
     *
     * @return void
     */
    public function render_meta_box($post, $args): void
    {
        echo '<div id="my_app_metabox"></div>';
    }


    /**
     * Add query var
     *
     * @param array $vars Vars.
     * @return array
     */
    public function add_query_var($vars)
    {
        $vars[] = $this->rewrite['var'];
        return $vars;
    }

    /**
     * Custom post type rewrite rules
     *
     * @param array $rules Rules.
     * @return array
     */
    public function custom_post_type_rewrite_rules($rules)
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        return $rules;
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     */
    public function save_metabox($post_id, $post)
    { // phpcs:ignore
                                                        // phpcs:disable
        /*
		* We need to verify this came from the our screen and with proper authorization,
		* because save_post can be triggered at other times.
		*/

        // Check if our nonce is set.
        if (! isset($_POST['_wpnonce'])) {
            return $post_id;
        }

        $nonce = $_POST['_wpnonce'];

        /*
		* If this is an autosave, our form has not been submitted,
		* so we don't want to do anything.
		*/
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $post_type = \sanitize_text_field($_POST['post_type'] ?? '');

        // Check the user's permissions.

        if ( 'wp-vote' !== $post_type ) {
        	return $post_id;
        }

        if (! \current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $meta_data = \sanitize_text_field( $_POST[ Plugin::$snake . '_meta' ] );

        // Update the meta field.
        \update_post_meta( $post_id, Plugin::$snake . '_meta', $meta_data );
        

    }

    /**
     * Load custom template
     * Set {Plugin::$kebab}/{slug}/report  php template
     *
     * @param string $template Template.
     */
    public function load_custom_template($template)
    {
        $repor_template_path = Plugin::$dir . '/inc/templates/' . $this->rewrite['template_path'];

        if (\get_query_var($this->rewrite['var'])) {
            if (file_exists($repor_template_path)) {
                return $repor_template_path;
            }
        }
        return $template;
    }
}

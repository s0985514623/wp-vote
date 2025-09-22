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
            add_shortcode('vote_form', [$this, 'vote_form_shortcode'], 10, 1);
            add_action('wp_ajax_vf_submit_vote', [$this, 'vf_handle_submit_vote']);
            add_action('wp_ajax_nopriv_vf_submit_vote', [$this, 'vf_handle_submit_vote']);
        }

        public function vote_form_shortcode($atts)
        {
            $atts = shortcode_atts([
                'post_id'        => '',
                'items'          => '',
                'gender_group'   => '',
                'age_group'      => '',
                'region_group'   => '',
                'startup_group'  => '',
                'identity_group' => '',
            ], $atts, 'vote_form');
            // 將逗號分隔字串轉成陣列（自動去除空白與空值）
            $to_array = function ($csv) {
                if (! is_string($csv) || trim($csv) === '') {
                    return [];
                }

                $arr = array_map('trim', explode(',', $csv));
                return array_values(array_filter($arr, fn($v) => $v !== ''));
            };
            $post_id        = esc_attr($atts['post_id']);
            $items          = $to_array($atts['items']);
            $gender_group   = $to_array($atts['gender_group']);
            $age_group      = $to_array($atts['age_group']);
            $region_group   = $to_array($atts['region_group']);
            $startup_group  = $to_array($atts['startup_group']);
            $identity_group = $to_array($atts['identity_group']);
            // 小工具：輸出一組 radio（單選）
            $render_radios = function ($name, $options, $required = false) {
                if (empty($options)) {
                    return '';
                }

                $required_attr = $required ? ' required' : '';
                $html          = '<div class="vf-fieldset">';
                foreach ($options as $idx => $label) {
                    $id  = esc_attr($name . '_' . $idx);
                    $val = esc_attr($label);
                    $lab = esc_html($label);
                    $lab = $this->value_to_label($lab);
                    if ($name === 'vote_item') {
                        // 將 label 拆成兩個
                        $lab  = explode(' - ', $lab);
                        $lab1 = $lab[0];
                        $lab2 = $lab[1];
                        $html .= "<label class=\"vf-choice\"><input type=\"radio\" id=\"{$id}\" name=\"{$name}\" value=\"{$lab1}\"{$required_attr}> <span><strong>{$lab1}</strong> - {$lab2}</span></label>";
                    } else {
                        $html .= "<label class=\"vf-choice\"><input type=\"radio\" id=\"{$id}\" name=\"{$name}\" value=\"{$val}\"{$required_attr}> <span>{$lab}</span></label>";
                    }
                }
                $html .= '</div>';
                return $html;
            };

            // 產生亂數驗證（加法）
            $a     = wp_rand(10, 99);
            $b     = wp_rand(10, 99);
            $sum   = $a + $b;
            $token = wp_generate_uuid4();
            set_transient("vf_captcha_$token", $sum, 10 * MINUTE_IN_SECONDS);

            $nonce    = wp_create_nonce('vf_vote');
            $ajax_url = admin_url('admin-ajax.php');

            //ob_start 應用
            $html = '';
            ob_start();
        ?>
            <form class="vf-form" method="post" id="vf-form" data-ajax-url="<?php echo esc_url($ajax_url); ?>">
                <div id="vf-msg" class="vf-alert" style="display:none"></div>

                <input type="hidden" name="action" value="vf_submit_vote">
                <input type="hidden" name="vf_nonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                <input type="hidden" id="vf_token" name="vf_token" value="<?php echo esc_attr($token); ?>">

                <!-- 票選項目（必填：單選） -->
                <fieldset class="vf-group vote_item">
                    <legend class="vf-legend">票選項目</legend>
                    <?php echo $render_radios('vote_item', $items, true); ?>
                </fieldset>

                <!-- 性別（單選） -->
                <?php if (! empty($gender_group)): ?>
                <fieldset class="vf-group">
                    <legend class="vf-legend">性別</legend>
                    <?php echo $render_radios('gender_group', $gender_group); ?>
                </fieldset>
                <?php endif; ?>

                <!-- 年齡（單選） -->
                <?php if (! empty($age_group)): ?>
                <fieldset class="vf-group">
                    <legend class="vf-legend">年齡</legend>
                    <?php echo $render_radios('age_group', $age_group); ?>
                </fieldset>
                <?php endif; ?>

                <!-- 地區（單選） -->
                <?php if (! empty($region_group)): ?>
                <fieldset class="vf-group">
                    <legend class="vf-legend">地區</legend>
                    <?php echo $render_radios('region_group', $region_group); ?>
                </fieldset>
                <?php endif; ?>

                <!-- 是否有創業的經驗（單選） -->
                <?php if (! empty($startup_group)): ?>
                <fieldset class="vf-group">
                    <legend class="vf-legend">是否有創業的經驗</legend>
                    <?php echo $render_radios('startup_group', $startup_group); ?>
                </fieldset>
                <?php endif; ?>

                <!-- 身分（單選） -->
                <?php if (! empty($identity_group)): ?>
                <fieldset class="vf-group">
                    <legend class="vf-legend">身分</legend>
                    <?php echo $render_radios('identity_group', $identity_group); ?>
                </fieldset>
                <?php endif; ?>

                <!-- 投票人姓名（文字） -->
                <div class="vf-group">
                    <label for="vf_name" class="vf-label">暱稱</label>
                    <input type="text" id="vf_name" name="voter_name" class="vf-input" placeholder="請輸入暱稱">
                </div>

                <!-- 意見留言（多行文字） -->
                <div class="vf-group">
                    <label for="vf_comment" class="vf-label">說說你的看法</label>
                    <textarea id="vf_comment" name="voter_comment" class="vf-textarea" rows="4" placeholder="想說的話…"></textarea>
                </div>

                <!-- 簡易亂數驗證（加法題） -->
                <!-- <div class="vf-group">
                    <label for="vf_answer" id="vf-q-label" class="vf-label">驗證：<?php echo esc_html($a . ' + ' . $b . ' = ?'); ?></label>
                    <input type="number" id="vf_answer" name="vf_answer" class="vf-input" inputmode="numeric" required>
                </div> -->

                <!-- 送出按鈕 -->
                <div class="vf-actions">
                    <button type="submit" class="vf-submit">送出投票</button>
                </div>
            </form>
            <script src="https://www.google.com/recaptcha/api.js?render=6Lcv7cYrAAAAAJVuxCgB6zwFKIYTD9fqtsnuyxiK"></script>
            <script>
                jQuery(function($){
                    var $form   = $('#vf-form');
                    var $msg    = $('#vf-msg');
                    var $button = $('#vf-submit');

                    $form.on('submit', function(e){
                        e.preventDefault();
                        // 取得表單資料
                        const formEl  = e.currentTarget;

                        // 重置訊息
                        $msg.removeClass('vf-ok vf-ng').hide().text('');
                        grecaptcha.ready(function() {
                            grecaptcha.execute("6Lcv7cYrAAAAAJVuxCgB6zwFKIYTD9fqtsnuyxiK", {action: "vote_submit"}).then(function(token) {
                                // 把 token 塞進隱藏欄位（若已存在就覆寫）
                                let tokenInput = formEl.querySelector('input[name="g-recaptcha-response"]');
                                if (!tokenInput) {
                                tokenInput = document.createElement('input');
                                tokenInput.type = 'hidden';
                                tokenInput.name = 'g-recaptcha-response';
                                formEl.appendChild(tokenInput);
                                }
                                tokenInput.value = token;

                                // 避免重複送出 + 啟用 loading 動畫
                                $button.prop('disabled', true).attr('aria-busy', 'true').addClass('is-loading');

                                // ✅ 用保存的 formEl 產生 FormData（避免 this 漂移）
                                const formData = new FormData(formEl);

                                $.ajax({
                                    url: $form.data('ajax-url'),
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    xhrFields: { withCredentials: true },
                                    dataType: 'json'
                                }).done(function(resp){
                                    if (resp && resp.success) {
                                        // 成功：當前頁面連結後加上?vote=result
                                        window.location.href = window.location.href + '?vote=result';
                                        // location.reload();
                                    } else {
                                        $msg.text((resp && (resp.message || resp.data?.message)) || '送出失敗')
                                            .addClass('vf-ng')
                                            .show();
                                    }
                                }).fail(function (jqXHR, textStatus, errorThrown) { // ✅ 正確參數順序
                                    console.log('AJAX fail:', textStatus, errorThrown, jqXHR);
                                    const resp = jqXHR.responseJSON || {};
                                    $msg.text(resp.message || '連線失敗，請稍後再試').addClass('vf-ng').show();
                                    $('html,body').animate({ scrollTop: $form.offset().top - 20 }, 300);
                                }).always(function(resp){
                                    // 還原按鈕狀態
                                    $button.prop('disabled', false).removeAttr('aria-busy').removeClass('is-loading');
                                });
                            });
                        });
                    });
                });
            </script>
            <style>
                html,body{
                    scroll-behavior: smooth;
                }
                .vf-form { max-width: 100%; margin-top:12px; padding:12px; border: 1px solid #eee; border-radius: 8px;padding-top: 60px; }
                .vf-group { margin-bottom: 16px; }
                .vf-legend { font-weight: 600; margin-bottom: 8px; }
                .vf-fieldset { display: flex; flex-wrap: wrap; gap: 8px; }
                .vote_item .vf-fieldset { flex-direction: column }
                .vote_item .vf-fieldset .vf-choice:not(:last-child) { border-bottom: 1px solid #ccc; }
                .vote_item .vf-fieldset span{font-size: 20px;}
                .vf-fieldset input[type="radio"]{width: 18px;height: 18px;}
                .vf-choice { display: inline-flex; align-items: center; gap: 6px;  padding-bottom: 8px;margin-bottom: 0;}
                .vf-label { display: block; margin-bottom: 6px; font-weight: 500; }
                .vf-input, .vf-textarea { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #ccc; border-radius: 6px; }
                .vf-actions { margin-top: 20px; }
                .vf-submit { position: relative; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; background: #222; color: #fff; }
                .vf-submit[disabled] { opacity: .7; cursor: not-allowed; }
                /* jQuery Loading 動畫（純 CSS spinner） */
                .vf-submit.is-loading .vf-submit__text { opacity: 0; }
                .vf-submit.is-loading::after {
                    content: "";
                    position: absolute;
                    inset: 0;
                    margin: auto;
                    width: 18px; height: 18px;
                    border-radius: 50%;
                    border: 2px solid #fff;
                    border-top-color: transparent;
                    animation: vf-spin .8s linear infinite;
                }
                @keyframes vf-spin { to { transform: rotate(360deg); } }

                .vf-alert { padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; }
                .vf-alert.vf-ok { background:#e8f7ee; border:1px solid #b8e6c8; color:#1e7e34; display:block!important;}
                .vf-alert.vf-ng { background:#fdecea; border:1px solid #f5c6cb; color:#8a1c1c; display:block!important;}
            </style>
        <?php
            $html .= ob_get_clean();
                    return $html;
                }

                /**
                 * AJAX 接收端：僅做亂數驗證 + error_log 所有參數
                 */
                public function vf_handle_submit_vote()
                {
                    // 基本 CSRF 驗證
                    if (! isset($_POST['vf_nonce']) || ! wp_verify_nonce($_POST['vf_nonce'], 'vf_vote')) {
                        wp_send_json_error(['message' => '非法請求（nonce 驗證失敗）'], 400);
                    }
                    error_log(print_r($_POST, true));

                    // 取表單參數（保留/調整）
                    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
                    $answer  = isset($_POST['vf_answer']) ? intval($_POST['vf_answer']) : null; // 若後面不用可移除

                    // 取得 reCAPTCHA token（前端名稱二擇一：g-recaptcha-response 或 g_recaptcha_token）
                    $recaptcha_token = '';
                    if (isset($_POST['g-recaptcha-response'])) {
                        $recaptcha_token = sanitize_text_field($_POST['g-recaptcha-response']);
                    }

                    // 驗證 reCAPTCHA v3
                    $verify = $this->vf_verify_recaptcha_v3($recaptcha_token, 'vote_submit', 0.5);
                    if (! $verify['ok']) {
                        wp_send_json_error([
                            'message' => 'reCAPTCHA 驗證失敗：' . $verify['reason'],
                            'debug'   => WP_DEBUG ? $verify['raw'] : null,
                        ], 400);
                    }

                    // ---- 到這裡：驗證通過 ----

                    if ($post_id <= 0) {
                        wp_send_json_error(['message' => '缺少 post_id'], 400);
                    }
                    // 1) vote_total +1
                    $total = (int) get_post_meta($post_id, 'vote_total', true);
                    update_post_meta($post_id, 'vote_total', $total + 1);

                    // 2) custom_pairs（對應所選票項目）
                    $selected_pair = isset($_POST['vote_item']) ? sanitize_text_field($_POST['vote_item']) : '';
                    if ($selected_pair === '') {
                        wp_send_json_error(['message' => '未選擇票選項目'], 400);
                    }
                    $this->vf_inc_custom_pairs($post_id, $selected_pair);

                    // 3) groups（映射）
                    $groups = ['gender_group', 'age_group', 'region_group', 'startup_group', 'identity_group'];
                    // $groups = ['gender_group'];
                    foreach ($groups as $group) {
                        if (isset($_POST[$group]) && $_POST[$group] !== '') {
                            $this->vf_inc_map_meta($post_id, $group, $_POST[$group]);
                        }
                    }
                    // 4) 新增留言（comment）並在該留言加上 meta: selected_pair
                    $author     = isset($_POST['voter_name']) ? sanitize_text_field($_POST['voter_name']) : '';
                    $content    = isset($_POST['voter_comment']) ? wp_kses_post($_POST['voter_comment']) : '';
                    $comment_id = wp_insert_comment([
                        'comment_post_ID'   => $post_id,
                        'comment_author'    => $author,
                        'comment_content'   => $content,
                        'comment_approved'  => 1,
                        'comment_author_IP' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '',
                        'comment_date'      => current_time('mysql'),
                    ]);
                    if ($comment_id && ! is_wp_error($comment_id)) {
                        add_comment_meta($comment_id, 'selected_pair', $selected_pair, true);
                    }

                    wp_send_json_success(['message' => '已記錄投票與留言。']);

                }

                /**
                 * 對 custom_pairs（array of ['text1'=>..., 'number'=>int]）的 number 做 +1
                 */
                public function vf_inc_custom_pairs($post_id, $label)
                {
                    $pairs = get_field('custom_pairs', $post_id);
                    if (! is_array($pairs)) {
                        $pairs = [];
                    }

                    foreach ($pairs as &$row) { // &參照，直接操作 $row
                                                    // 允許既有結構：'text1' 都視為標籤
                        $rlabel = $row['text1'] ?? null;
                        if ($rlabel !== null && (string) $rlabel === (string) $label) {
                            $row['number'] = isset($row['number']) ? (int) $row['number'] + 1 : 1;
                            break;
                        }
                    }
                    //解除參照，避免後面不小心動到最後一筆。
                    unset($row);

                    update_field('custom_pairs', $pairs, $post_id);
                }

                /**
                 * 驗證 Google reCAPTCHA v3
                 *
                 * @param string $token   前端 grecaptcha.execute() 拿到的 token
                 * @param string $action  你在前端 execute 時設定的 action（例如 'vote_submit'）
                 * @param float  $threshold 最低分數（0~1，建議 0.5）
                 * @return array ['ok' => bool, 'reason' => string, 'raw' => array]
                 */
                public function vf_verify_recaptcha_v3($token, $action = 'vote_submit', $threshold = 0.5)
                {
                    // 建議放在 wp-config.php 或是用 get_option() 取設定
                    $secret = '6Lcv7cYrAAAAAJnLjEY_QAq5oFnH42Wi2ge_huYi';

                    if (empty($secret)) {
                        return ['ok' => false, 'reason' => 'reCAPTCHA 秘鑰未設定', 'raw' => []];
                    }
                    if (empty($token)) {
                        return ['ok' => false, 'reason' => '缺少 reCAPTCHA token', 'raw' => []];
                    }

                    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                        'timeout' => 10,
                        'body'    => [
                            'secret'   => $secret,
                            'response' => $token,
                            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
                        ],
                    ]);

                    if (is_wp_error($response)) {
                        return ['ok' => false, 'reason' => '連線驗證服務失敗', 'raw' => ['error' => $response->get_error_message()]];
                    }

                    $data = json_decode(wp_remote_retrieve_body($response), true);
                    if (! is_array($data)) {
                        return ['ok' => false, 'reason' => '驗證回應格式錯誤', 'raw' => []];
                    }

                    // 基本 success
                    if (empty($data['success'])) {
                        // 可能包含 error-codes
                        return ['ok' => false, 'reason' => 'reCAPTCHA 驗證未通過', 'raw' => $data];
                    }

                    // 分數與 action 檢查（v3 重要）
                    $score_ok  = isset($data['score']) ? ((float) $data['score'] >= (float) $threshold) : false;
                    $action_ok = isset($data['action']) ? ($data['action'] === $action) : false;

                    if (! $score_ok) {
                        return ['ok' => false, 'reason' => 'reCAPTCHA 分數過低', 'raw' => $data];
                    }
                    if (! $action_ok) {
                        return ['ok' => false, 'reason' => 'reCAPTCHA 動作不符', 'raw' => $data];
                    }

                    return ['ok' => true, 'reason' => 'ok', 'raw' => $data];
                }

                /**
                 * 對「map 型」計數的 post meta 做 +1（meta 存成 ['label' => count, ...]）
                 */
                public function vf_inc_map_meta($post_id, $meta_key, $label)
                {
                    $map = get_field($meta_key, $post_id);
                    if (! is_array($map)) {
                        $map = [];
                    }

                    $key       = (string) $label;
                    $map[$key] = isset($map[$key]) ? (int) $map[$key] + 1 : 1;
                    update_field($meta_key, $map, $post_id);
                }
                /**
                 * 產生新題目 & token
                 */
                public function vf_new_captcha()
                {
                    $a     = wp_rand(10, 99);
                    $b     = wp_rand(10, 99);
                    $sum   = $a + $b;
                    $token = wp_generate_uuid4();
                    set_transient("vf_captcha_$token", $sum, 10 * MINUTE_IN_SECONDS);
                    return [
                        'token'    => $token,
                        'question' => "$a + $b = ?",
                    ];
                }
                /**
                 * 將值轉換為標籤
                 */
                public function value_to_label($value)
                {
                    switch ($value) {
                        case 'male':
                            return '男生';
                        case 'female':
                            return '女生';
                        case 'under_20':
                            return '20歲以下';
                        case 'age_21_30':
                            return '21–30歲';
                        case 'age_31_40':
                            return '31–40歲';
                        case 'age_41_50':
                            return '41–50歲';
                        case 'age_51_60':
                            return '51–60歲';
                        case 'over_60':
                            return '60歲以上';
                        case 'north':
                            return '北部';
                        case 'central':
                            return '中部';
                        case 'south':
                            return '南部';
                        case 'east':
                            return '東部';
                        case 'yes':
                            return '有';
                        case 'no':
                            return '無';
                        case 'student':
                            return '學生';
                        case 'office_worker':
                            return '上班族';
                        case 'self_employed':
                            return '自營業';
                        case 'retired':
                            return '退休';
                        case 'other':
                            return '其他';
                        default:
                            return $value;
                    }
                }
        }

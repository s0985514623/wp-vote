<?php
/**
 * Custom ACF: Wp Vote
 * 增加ACF設定
 */

declare (strict_types = 1);

namespace Ren\WpVote\Classes;

use Ren\WpVote\Plugin;

/**
 * Class CPT
 */
final class ACF
{
    use \J7\WpUtils\Traits\SingletonTrait;
    public function __construct()
    {
        add_action('acf/init', function () {
            if (!function_exists('acf_add_local_field_group')) return;
        
            acf_add_local_field_group([
                'key'   => 'group_vote_target_fields',
                'title' => '投票設定',
                'fields' => [
                    //投票總人數
                    [
                        'key'    => 'field_vote_total_group',
                        'label'  => '投票總人數',
                        'name'   => 'vote_total',
                        'type'   => 'number',
                        'min'    => 0,
                        'default_value' => 0,
                    ],
                    [
                        'key'    => 'field_vote_dates_group',
                        'label'  => '投票資訊',
                        'name'   => 'vote_dates',
                        'type'   => 'group',
                        'layout' => 'table',
                        'sub_fields' => [
                            [
                                'key'            => 'field_vote_date_start',
                                'label'          => '統計開始日',
                                'name'           => 'stat_start',
                                'type'           => 'date_picker',
                                'display_format' => 'Y-m-d',
                                'return_format'  => 'U',  // timestamp，程式好處理
                                'first_day'      => 1,
                            ],
                            [
                                'key'            => 'field_vote_date_end',
                                'label'          => '統計結束日',
                                'name'           => 'stat_end',
                                'type'           => 'date_picker',
                                'display_format' => 'Y-m-d',
                                'return_format'  => 'U',
                                'first_day'      => 1,
                            ],
                        ],
                    ],
                    // 1) 自訂配對：文字1 + 文字2 + 數字
                    [
                        'key'           => 'field_pairs',
                        'label'         => '投票項目',
                        'name'          => 'custom_pairs',
                        'type'          => 'repeater',
                        'layout'        => 'table',
                        'button_label'  => '新增一列',
                        'sub_fields'    => [
                            [
                                'key'   => 'field_pairs_text1',
                                'label' => '項目',
                                'name'  => 'text1',
                                'type'  => 'text',
                            ],
                            [
                                'key'   => 'field_pairs_text2',
                                'label' => '項目說明',
                                'name'  => 'text2',
                                'type'  => 'text',
                            ],
                            [
                                'key'           => 'field_pairs_number',
                                'label'         => '數字',
                                'name'          => 'number',
                                'type'          => 'number',
                                'min'           => 0,
                                'step'          => 1,
                                'default_value' => 0,
                            ],
                        ],
                    ],
        
                    [ 'key' => 'field_tab_vote', 'label' => '投票屬性', 'type' => 'tab' ],
        
                    // 2) 性別（Group）
                    [
                        'key'     => 'field_gender_group',
                        'label'   => '性別',
                        'name'    => 'gender_group',
                        'type'    => 'group',
                        'layout'  => 'table',
                        'sub_fields' => [
                            [
                                'key'           => 'field_gender_male',
                                'label'         => '男生',
                                'name'          => 'male',
                                'type'          => 'number',
                                'min'           => 0,
                                'step'          => 1,
                                'default_value' => 0,
                            ],
                            [
                                'key'           => 'field_gender_female',
                                'label'         => '女生',
                                'name'          => 'female',
                                'type'          => 'number',
                                'min'           => 0,
                                'step'          => 1,
                                'default_value' => 0,
                            ],
                        ],
                    ],
        
                    // 3) 年齡層（Group）
                    [
                        'key'     => 'field_age_group',
                        'label'   => '年齡層',
                        'name'    => 'age_group',
                        'type'    => 'group',
                        'layout'  => 'table',
                        'sub_fields' => [
                            [ 'key' => 'field_age_u20',   'label' => '20歲以下', 'name' => 'under_20',  'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_age_21_30','label' => '21–30歲',  'name' => 'age_21_30','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_age_31_40','label' => '31–40歲',  'name' => 'age_31_40','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_age_41_50','label' => '41–50歲',  'name' => 'age_41_50','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_age_51_60','label' => '51–60歲',  'name' => 'age_51_60','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_age_o60',  'label' => '60歲以上', 'name' => 'over_60',   'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                        ],
                    ],
        
                    // 4) 所在縣市區域（Group）
                    [
                        'key'     => 'field_region_group',
                        'label'   => '所在縣市區域',
                        'name'    => 'region_group',
                        'type'    => 'group',
                        'layout'  => 'table',
                        'sub_fields' => [
                            [ 'key' => 'field_region_north',  'label' => '北部地區', 'name' => 'north',  'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_region_central','label' => '中部地區', 'name' => 'central','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_region_south',  'label' => '南部地區', 'name' => 'south',  'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_region_east',   'label' => '東部地區', 'name' => 'east',   'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                        ],
                    ],
        
                    // 5) 是否已有創業經驗（Group）
                    [
                        'key'     => 'field_startup_group',
                        'label'   => '是否已有創業經驗',
                        'name'    => 'startup_group',
                        'type'    => 'group',
                        'layout'  => 'table',
                        'sub_fields' => [
                            [ 'key' => 'field_startup_yes', 'label' => '有', 'name' => 'yes', 'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_startup_no',  'label' => '無', 'name' => 'no',  'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                        ],
                    ],
        
                    // 6) 目前身份（Group）
                    [
                        'key'     => 'field_identity_group',
                        'label'   => '目前身份',
                        'name'    => 'identity_group',
                        'type'    => 'group',
                        'layout'  => 'table',
                        'sub_fields' => [
                            [ 'key' => 'field_identity_student',      'label' => '學生',   'name' => 'student',      'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_identity_office',       'label' => '上班族', 'name' => 'office_worker','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_identity_self_employed','label' => '自營業', 'name' => 'self_employed','type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                            [ 'key' => 'field_identity_retired',      'label' => '退休',   'name' => 'retired',      'type' => 'number', 'min' => 0, 'step' => 1, 'default_value' => 0 ],
                        ],
                    ],
        
                ],
                'location' => [
                    [
                        [ 'param' => 'post_type', 'operator' => '==', 'value' => 'vote' ],
                    ],
                ],
                'position' => 'normal',
                'style'    => 'default',
                'active'   => true,
            ]);
        });
        
    }

}
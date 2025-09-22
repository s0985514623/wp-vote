<?php
    /**
     * Template Name: Vote Page single
     * Template Post Type: page
     */

get_header(); ?>
<style>
  /* 圖片 + overlay */
  .vote-hero__banner {
        position: relative;
        min-height: 500px;
        aspect-ratio: 880/500;
        width: 100%;
    }
    .vote-hero__banner img {
        width: 100%;
        display: block;
        height: 100%;
        object-fit: cover;
    }
    .vote-hero__overlay {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 28px;
        background: linear-gradient(
            180deg,
            rgba(0, 0, 0, 0) 0%,
            rgba(0, 0, 0, 0.6) 60%,
            rgba(0, 0, 0, 0.75) 100%
        );
        color: #fff;
    }
    .vote-hero__overlay a {
        color: inherit;
        font-size: 36px;
        line-height: 46px;
        text-decoration: none;
    }
    .vote-hero__overlay strong {
        margin: 0 0 6px;
        font-size: 28px;
        line-height: 1.3;
    }
    .vote-hero__overlay p {
        margin: 0;
        opacity: 0.9;
    }
    .vote-hero__content {
        margin: 0;
        opacity: 0.9;
        font-size: 15px;
    }
    .vote-hero__title{
      font-size: 20px;
      font-weight: 600;
      padding: 28px;
    }
    .vote-hero__meta{
      margin-top: 10px;
      display: flex;
      gap: 10px;
      align-items: center;
      justify-content: space-between;
      padding-left: 28px;
      font-size: 15px;
    }
    .vote-hero_button_wrap{
      display: flex;
      gap: 10px;
      align-items: start;
      justify-content: start;
    }
    .vote-hero_button{
      padding: 10px 16px;
      color: #fff;
      border-radius: 3px;
      border: 1px #ccc solid;
      text-decoration: none;
      font-weight: 600;
      font-size: 15px;
      color:#9A450E;
    }
    .vote-hero__item{
      border-bottom: 1px #ccc dashed;
      padding: 15px;
    }
    .vote-hero__item_bar{
      display: flex;
      margin: 15px 15px 0px 15px;
      align-items: center;
    }
    .vote-hero__item_bar_percent{
      font-size: 20px;
      color:#FF6E00;
      width: 80px;
      float: left;
    }
    .vote-hero__item_title{
      padding: 20px 0;
      font-size: 20px;
      /* border-top: 2px solid #ccc; */
      width: 100%;
      margin-top: 40px;
    }
    .bank_03{
      font-size: 20px;
      padding: 5px 10px;
    }
    .bar2{
      height: 20px;
      background-color: #eaeaea;
      float: left;
      width: 80%;
      border-radius: 7px;
      overflow: hidden;
    }
    .bar2::before {
    content: '';
    display: flex;
    justify-content: end;
    width: calc(var(--percent) * 1%);
    max-width: 100%;
    height: 100%;
    background: #2486ff;
    white-space: nowrap;
    border-radius: 7px 0 0 7px;
    }
    .vote-hero__comment_wrap{
      margin-bottom: 30px;
    }
    .vote-comment{
      padding: 10px 0;
      font-size: 15px;
    }
    /* RWD */
    @media screen and (max-width: 769px){
        .vote-hero__banner{
            min-height: 250px;

        }
        .vote-hero__overlay a {
            font-size: 24px;
            line-height: 1em;
        }
        .vote-hero__meta{
          padding-left: 6px;
          font-size: 12px;
          gap: 4px;
        }
        .vote-hero__title{
          padding: 14px;
        }
        .vote-hero__item{
          padding: 14px;
        }
        .bank_03{
          padding: 5px 0px ;
        }
        .vote-hero__item_bar_percent , .vote-hero__item_title , .bank_03{
          font-size: 20px;
        }
        .vote-hero__item_bar{
          margin-top: 15px;
        }
        .vote-hero_button{
          font-size: 12px;
          padding: 2px 5px;
        }
      }
</style>
<?php
    $voteResult = false;
    //取得vote Query String 參數
    if (isset($_GET['vote'])&& $_GET['vote'] == 'result') {
      $voteResult = true;
  }

    // 取得 slug 為 'vote' 的頁面
    $page          = get_page_by_path('vote', OBJECT, 'page');
    $vote_page_url = get_permalink($page->ID);
    $banner        = '';
    if ($page instanceof WP_Post) {
        // 取該頁面的特色圖片 URL（尺寸可改 'full', 'large', 'medium' 等）
        $banner = get_the_post_thumbnail_url($page->ID, 'full') ?: '';
    }
    if ($banner != '') {
        echo '<section class="archive-banner">
    <img src="' . $banner . '">
  </section>';
    }
    // 取得市調列表
    $votes = new WP_Query([
        'post_type'      => 'vote',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
?>

<section class="section page">
  <div class="container">
    <div class="row">
      <div class="col-lg-9">
      <?php
          $id      = get_the_ID();
          $title   = get_the_title();
          $link    = get_the_permalink();
          $img     = get_the_post_thumbnail_url($id, 'full');
          $content = get_the_content(null, false, $id);
          //投票總人數
          $vote_total = get_field('vote_total');
          //投票項目
          $items = get_field('custom_pairs');
          //投票資訊
          $vote_dates = get_field('vote_dates');
          if (! empty($vote_dates['stat_start']) && ! empty($vote_dates['stat_end'])) {
              $vote_dates_text = date('Y/m/d', $vote_dates['stat_start']) . ' ~ ' . date('Y/m/d', $vote_dates['stat_end']);
          } else {
              $vote_dates_text = '';
          }
          //性別（Group）
          $gender_group = get_field('gender_group');
          //年齡層（Group）
          $age_group = get_field('age_group');
          //所在縣市區域（Group）
          $region_group = get_field('region_group');
          //是否已有創業經驗（Group）
          $startup_group = get_field('startup_group');
          //目前身份（Group）
          $identity_group = get_field('identity_group');
          //留言
          $comments = get_comments([
              'post_id' => $id,
              'status'  => 'approve',
              'orderby' => 'comment_date_gmt',
              'order'   => 'DESC',
          ]);
          $comment_html = '';
          if ($comments) {
              foreach ($comments as $c) {
                  $author  = get_comment_author($c);
                  $excerpt = $c->comment_content;
                  $selected_pair = get_comment_meta($c->comment_ID, 'selected_pair', true);
                  if($excerpt!=''){
                    $comment_html .= '<div class="vote-comment">
                                          <img src="' . plugin_dir_url(dirname(__FILE__)) . '/Asset/img/call_3.png' . '"
                                              alt="' . esc_attr($author) . '"
                                              width="60" height="60">
                                          <strong><span style="color:#004BD0;">' . esc_html($author) . '</span> - ' . esc_html($selected_pair) . '</strong>： <strong>' . esc_html($excerpt) . '</strong>
                                      </div>';
                  }
              }
          }
          //判斷當前IP是否投過票
          $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
          $has_voted = false;
          if ($comments) {
              foreach ($comments as $c) {
                  $ip = get_comment_meta($c->comment_ID, 'comment_author_IP', true);
            }
            if ($ip == $ip) {
                $has_voted = true;
            }
          }
          //判斷當前日期是否在統計時間內
          $is_in_stat_time = false;
          if ($vote_dates['stat_start'] && $vote_dates['stat_end']) {

            $current_date = date('Y-m-d');
            //將current_date轉換為unix時間
            $current_date_unix = strtotime($current_date);
            if ($current_date_unix >= $vote_dates['stat_start'] && $current_date_unix <= $vote_dates['stat_end']) {
              $is_in_stat_time = true;
            }
          }
      ?>
        <div class="vote-hero__banner" >
            <?php if ($img): ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>" >
            <?php endif; ?>
            <div class="vote-hero__overlay" >
                <a href="<?php echo esc_url($link); ?>" >
                <strong ><?php echo esc_html($title); ?></strong>
                </a>
                <?php if ($content): ?>
                    <p class="vote-hero__content"><?php echo esc_html($content); ?></p>
                <?php endif; ?>
            </div>
        </div>
            <div class="vote-hero__meta">
                <?php if ($vote_dates_text): ?>
                    <span class="vote-hero_time">統計時間：<?php echo esc_html($vote_dates_text); ?></span>
                <?php endif; ?>
                <div class="vote-hero_button_wrap">
                    <?php if (!$is_in_stat_time): ?>
                      <span class="vote-hero_button">已完成投票</span>
                    <?php elseif($voteResult): ?>
                      <a href="<?php echo esc_url($link); ?>" class="vote-hero_button">
                        來去投票
                      </a>
                    <?php else: ?>
                      <a href="?vote=result" class="vote-hero_button">
                        投票結果
                      </a>
                    <?php endif; ?>
                  <a href="<?php echo esc_url($vote_page_url); ?>" class="vote-hero_button">
                    市調首頁
                  </a>
                </div>
            </div>
            <?php if($voteResult|| !$is_in_stat_time ): ?>
              <div class="vote-hero__title" >
                <?php echo $title; ?>
              </div>
              <?php
                  if ($items) {
                      foreach ($items as $item) {
                          $item_name           = $item['text1'];
                          $item_description    = $item['text2'];
                          $item_number         = $item['number'];
                          $item_number_percent = $vote_total != 0 ? $item_number / $vote_total * 100 : 0;
                          //percent只留整數
                          $item_number_percent = round($item_number_percent);
                      ?>
                    <div class="vote-hero__item" >
                      <div class="bank_03">
                        <div><strong><?php echo $item_name; ?></strong><span style="color: #666"> -<?php echo $item_description; ?></span>
                        </div>
                      </div>
                        <div class="vote-hero__item_bar">
                          <div class="vote-hero__item_bar_percent">
                                            <?php echo $item_number_percent; ?>%</div>
                            <div class="bar2" style="--percent:                                                              
                            <?php echo $item_number_percent; ?>;"></div>

                        </div>
                    </div>
                    <?php
                        }
                        }
                    ?>
              <div class="vote-hero__item_title">
                <strong>性別占比</strong>
              </div>
              <?php
                  if ($gender_group) {
                      foreach ($gender_group as $key => $item) {
                          /**
                           * Array
                           * (
                           *     [male] => 0
                           *     [female] => 0
                           * )
                           */
                          $item_name           = $key == 'male' ? '男生' : '女生';
                          $item_number_percent = $vote_total != 0 ? $item / $vote_total * 100 : 0;
                          //percent只留整數
                          $item_number_percent = round($item_number_percent);
                      ?>
                    <div class="vote-hero__item" >
                      <div class="bank_03">
                        <div><strong><?php echo $item_name; ?></strong></div>
                      </div>
                        <div class="vote-hero__item_bar">
                          <div class="vote-hero__item_bar_percent">
                                            <?php echo $item_number_percent; ?>%</div>
                            <div class="bar2" style="--percent:                                                              
                            <?php echo $item_number_percent; ?>;"></div>

                        </div>
                    </div>

                    <?php
                        }
                        }
                    ?>
              <div class="vote-hero__item_title">
                <strong>年齡層占比</strong>
              </div>
              <?php
                  if ($age_group) {
                      foreach ($age_group as $key => $item) {
                          $item_name = '';
                          switch ($key) {
                              case 'under_20':
                                  $item_name = '20歲以下';
                                  break;
                              case 'age_21_30':
                                  $item_name = '21–30歲';
                                  break;
                              case 'age_31_40':
                                  $item_name = '31–40歲';
                                  break;
                              case 'age_41_50':
                                  $item_name = '41–50歲';
                                  break;
                              case 'age_51_60':
                                  $item_name = '51–60歲';
                                  break;
                              case 'over_60':
                                  $item_name = '60歲以上';
                                  break;
                              default:
                                  $item_name = '';
                                  break;
                          }
                          if ($item_name == '') {
                              continue;
                          }
                          $item_number_percent = $vote_total != 0 ? $item / $vote_total * 100 : 0;
                          //percent只留整數
                          $item_number_percent = round($item_number_percent);
                      ?>
                    <div class="vote-hero__item" >
                      <div class="bank_03">
                        <div><strong><?php echo $item_name; ?></strong></div>
                      </div>
                        <div class="vote-hero__item_bar">
                          <div class="vote-hero__item_bar_percent">
                                            <?php echo $item_number_percent; ?>%</div>
                            <div class="bar2" style="--percent:                                                              
                            <?php echo $item_number_percent; ?>;"></div>

                        </div>
                    </div>
                    <?php
                        }
                        }
                    ?>
              <div class="vote-hero__item_title">
                <strong>所在縣市區域占比</strong>
              </div>
              <?php
                  if ($region_group) {
                      foreach ($region_group as $key => $item) {
                          $item_name = '';
                          switch ($key) {
                              case 'north':
                                  $item_name = '北部地區';
                                  break;
                              case 'central':
                                  $item_name = '中部地區';
                                  break;
                              case 'south':
                                  $item_name = '南部地區';
                                  break;
                              case 'east':
                                  $item_name = '東部地區';
                                  break;
                          }
                          if ($item_name == '') {
                              continue;
                          }
                          $item_number_percent = $vote_total != 0 ? $item / $vote_total * 100 : 0;
                          //percent只留整數
                          $item_number_percent = round($item_number_percent);
                      ?>
                    <div class="vote-hero__item" >
                      <div class="bank_03">
                        <div><strong><?php echo $item_name; ?></strong></div>
                      </div>
                        <div class="vote-hero__item_bar">
                          <div class="vote-hero__item_bar_percent">
                                            <?php echo $item_number_percent; ?>%</div>
                            <div class="bar2" style="--percent:                                                              
                            <?php echo $item_number_percent; ?>;"></div>

                        </div>
                    </div>
                    <?php
                        }
                        }
                    ?>
              <div class="vote-hero__item_title">
                <strong>是否已有創業經驗占比</strong>
              </div>
              <?php
                  if ($startup_group) {
                      foreach ($startup_group as $key => $item) {
                          $item_name           = $key == 'yes' ? '有' : '無';
                          $item_number_percent = $vote_total != 0 ? $item / $vote_total * 100 : 0;
                          //percent只留整數
                          $item_number_percent = round($item_number_percent);
                      ?>
                    <div class="vote-hero__item" >
                      <div class="bank_03">
                        <div><strong><?php echo $item_name; ?></strong></div>
                      </div>
                        <div class="vote-hero__item_bar">
                          <div class="vote-hero__item_bar_percent">
                              <?php echo $item_number_percent; ?>%</div>
                            <div class="bar2" style="--percent:                                                              
                            <?php echo $item_number_percent; ?>;"></div>
                        </div>
                    </div>
                    <?php
                        }
                        }
                    ?>
              <div class="vote-hero__item_title">
                <strong>目前身份占比</strong>
              </div>
              <?php
                  if ($identity_group) {
                      foreach ($identity_group as $key => $item) {
                          $item_name = '';
                          switch ($key) {
                              case 'student':
                                  $item_name = '學生';
                                  break;
                              case 'office_worker':
                                  $item_name = '上班族';
                                  break;
                              case 'self_employed':
                                  $item_name = '自營業';
                                  break;
                              case 'retired':
                                  $item_name = '退休';
                                  break;
                              case 'other':
                                  $item_name = '其他';
                                  break;
                          }
                          if ($item_name == '') {
                              continue;
                          }
                          $item_number_percent = $vote_total != 0 ? $item / $vote_total * 100 : 0;
                          //percent只留整數
                          $item_number_percent = round($item_number_percent);
                      ?>
                    <div class="vote-hero__item" >
                      <div class="bank_03">
                        <div><strong><?php echo $item_name; ?></strong></div>
                      </div>
                        <div class="vote-hero__item_bar">
                          <div class="vote-hero__item_bar_percent">
                                            <?php echo $item_number_percent; ?>%</div>
                            <div class="bar2" style="--percent:                                                              
                            <?php echo $item_number_percent; ?>;"></div>
                        </div>
                    </div>
                    <?php
                        }
                        }
                    ?>
              <div class="vote-hero__item_title" style="font-size: 24px;">
                <strong>看看大家怎麼說</strong>
              </div>
              <div class="vote-hero__comment_wrap">
                <?php echo $comment_html; ?>
              </div>
            <?php endif; ?>
            <?php 
            // 投票表單
            if ($is_in_stat_time &&  !$voteResult){
              $item_name_array =[];
              foreach ($items as $item) {
                if(isset($item['text2'])&& $item['text2']!=''){
                  $item_name_array[] = $item['text1'].' - '.$item['text2'];
                }else{
                  $item_name_array[] = $item['text1'];
                }
              }
              $item_name_array = implode(',',$item_name_array);
              $gender_group_array =[];
              foreach ($gender_group as $key => $item) {
                  $gender_group_array[] = $key;
              }
              $gender_group_array = implode(',',$gender_group_array);
              $age_group_array =[];
              foreach ($age_group as $key => $item) {
                  $age_group_array[] = $key;
              }
              $age_group_array = implode(',',$age_group_array);
              $region_group_array =[];
              foreach ($region_group as $key => $item) {
                  $region_group_array[] = $key;
              }
              $region_group_array = implode(',',$region_group_array);
              $startup_group_array =[];
              foreach ($startup_group as $key => $item) {
                  $startup_group_array[] = $key;
              }
              $startup_group_array = implode(',',$startup_group_array);
              $identity_group_array =[];
              foreach ($identity_group as $key => $item) {
                  $identity_group_array[] = $key;
              }
              $identity_group_array = implode(',',$identity_group_array);
              echo do_shortcode('[vote_form post_id="' . $id . '" items="' . $item_name_array . '" gender_group="' . $gender_group_array . '" age_group="' . $age_group_array . '" region_group="' . $region_group_array . '" startup_group="' . $startup_group_array . '" identity_group="' . $identity_group_array . '"]');
            }
            ?>
      </div>
      <div class="col-lg-3">
        <?php get_template_part('brand', 'sidebar'); ?>
      </div>
    </div>
  </div>
</section>
<?php get_footer();

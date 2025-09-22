<?php
    /**
     * Template Name: Vote Page Archive
     * Template Post Type: page
     */

get_header(); ?>
<style>
    
    /* 留言樣式 */
    .vote-comment {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        border-bottom: 1px dashed #ccc;
        padding: 10px 0;
    }
    .vote-comment img {
        width: 30px;
        height: 30px;
    }

    /* 第一篇投票 Hero 區塊 */
    .vote-hero {
        border: 1px dashed #eee;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    /* 圖片 + overlay */
    .vote-hero__banner {
        position: relative;
        min-height: 400px;
        aspect-ratio: 880/400;
        width: 100%;
    }
    .vote-hero__banner img {
        width: 100%;
        display: block;
        height: 100%;
        object-fit: cover;
    }
    .vote-hero__content {
        margin: 0;
        opacity: 0.9;
        font-size: 15px;
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

    /* 內文 body */
    .vote-hero__body {
        display: flex;
        align-items: center;
        gap: 16px;
        justify-content: space-between;
        padding: 18px 22px;
        background: #fff;
    }
    .vote-hero__left {
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        gap: 4px;
        flex: 1;
        min-width: 0;
    }
    .vote-hero__left .vote-label {
        padding: 3px;
        border: 1px dashed #ccc;
        border-radius: 5px;
        width: 95px;
        text-align: center;
        color: #999;
        margin-bottom: 5px;
        font-size: 15px;
    }
    .vote-hero__meta {
        color: #666;
        /* white-space: nowrap; */
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 15px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .vote-hero__right a {
        display: inline-block;
        background: #cc0000;
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    /* 留言列表 */
    .vote-hero__comments {
        background: #fff;
        padding: 0 22px 18px;
        font-size: 15px;
    }
    .vote-hero__comments > div {
        border-bottom: 1px dashed #ccc;
    }

    /* 更多創業市調 標題 */
    .more-votes {
        padding: 20px 0;
        font-size: 20px;
        width: 100%;
        margin-top: 20px;
    }
  .vote-card{
    display:grid;
    grid-template-columns: 260px 1fr;
    gap:16px;
    align-items:start;
    padding:12px;
    border:1px solid #e6e6e6;
    border-radius:8px;
    background:#fff;
    max-width:900px;
    margin-bottom: 10px;
  }
  .vote-thumb img{
    width:100%;
    height:auto;
    border-radius:6px;
    display:block;
    object-fit:cover;
    aspect-ratio: 258/165;
    object-fit: cover;
  }
  .vote-header{
    display:flex;
    gap:16px;
    justify-content:space-between;
    align-items:flex-start;
  }
  .vote-dates{
    font-size:12px;
    color:#9aa0a6;
    margin-bottom:4px;
  }
  .vote-title{
    margin:0 0 6px 0;
    font-size:20px;
    line-height:1.4;
  }
  .vote-excerpt{
    margin:0 0 8px 0;
    color:#9aa0a6;
    font-size:14px;
    line-height:1.6;
    max-width:52ch;
  }
  .vote-cta{ flex:0 0 auto; }
  .vote-btn{
    display:inline-block;
    padding:10px 16px;
    background:#cc0000;
    color:#fff;
    border-radius:6px;
    text-decoration:none;
    font-weight:700;
    transition:opacity .15s ease-in-out;
    white-space:nowrap;
  }
  .vote-btn:hover{
    color: #fff;
  }
  .vote-btn:hover{ opacity:.9; }

  .vote-comments{
    list-style:none;
    margin:6px 0 0;
    padding:0;
    border-top:1px dashed #ddd;
    font-size:15px;
  }
  
  .vote-others-wrap{
    margin-bottom: 20px;
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
        .vote-hero_label{
            display: none;
        }
        .vote-hero_label{
            display: none;
        }
        .vote-hero_time{
            font-size: 12px;
        }
        .vote-hero__body{
            padding: 12px 6px;
        }
        .vote-hero__right a{
            font-size: 15px;
            padding: 6px 10px;
        }
        .vote-hero__comments{
            padding: 0 6px 12px;
        }
        .vote-card{         
            grid-template-columns: 125px 1fr;
            gap: 6px; 
        }
        .vote-thumb img{
            aspect-ratio: 4/3;
            object-fit: cover;
        }
        .vote-header{
            flex-direction: column;
            gap: 4px;
        }
        .vote-header-content .vote-title{
            font-size: 18px;
        }
        .vote-excerpt{
            display: none;
        }
        .vote-cta{
            align-self: flex-end;
        }
        .vote-btn{
            font-size: 15px;
            padding: 6px 10px;
        }
        .vote-comments_mobile{
            grid-column: span 2;
            font-size: 15px;
        }
    }

</style>
<?php
    $banner = get_the_post_thumbnail_url();
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
          if ($votes->have_posts()):
              // 1) 第一篇
              $votes->the_post();
              $first_id      = get_the_ID();
              $first_title   = get_the_title();
              $first_link    = get_permalink();
              $first_img     = get_the_post_thumbnail_url($first_id, 'full');
              $first_content = get_the_content();
              //投票項目
              $items_text   = [];
              $items_labels = get_field('custom_pairs');
              if($items_labels){
                foreach ($items_labels as $item) {
                    $items_text[] = $item['text1'];
                }}
              $items_text = $items_text?implode('、', $items_text):'';
              //投票資訊
              $vote_dates      = get_field('vote_dates');
              if(!empty($vote_dates['stat_start']) && !empty($vote_dates['stat_end'])){
                $vote_dates_text = date('Y/m/d', $vote_dates['stat_start']) . ' ~ ' . date('Y/m/d', $vote_dates['stat_end']);
              }else{
                $vote_dates_text = '';
              }
            //   $vote_dates_text = $vote_dates?date('Y/m/d', $vote_dates['stat_start']) . ' ~ ' . date('Y/m/d', $vote_dates['stat_end']):'';
              //留言
              $comments = get_comments([
                  'post_id' => $first_id,
                  'number'  => 3,
                  'status'  => 'approve',
                  'orderby' => 'comment_date_gmt',
                  'order'   => 'DESC',
              ]);
              $comment_html = '';
              if ($comments) {
                  foreach ($comments as $c) {
                      $author  = get_comment_author($c);
                      $excerpt = wp_html_excerpt(
                          wp_strip_all_tags($c->comment_content),
                          60,
                          '…'
                      );
                      $selected_pair = get_comment_meta($c->comment_ID, 'selected_pair', true);
                      if($excerpt!=''){
                      $comment_html .= '<div class="vote-comment">
						                            <img src="' . plugin_dir_url(dirname(__FILE__)) . '/Asset/img/call_3.png' . '"
						                                 alt="' . esc_attr($author) . '"
						                                 width="30" height="30">
						                            <strong><span style="color:#004BD0;">' . esc_html($author) . '</span> - ' . esc_html($selected_pair) . '</strong>： <strong>' . esc_html($excerpt) . '</strong>
						                        </div>';
                      }
                  }
              }
          ?>
                <!-- 第一篇：圖片 Hero 區塊 -->
                <article class="vote-hero" >
                        <div class="vote-hero__banner" >
                            <a href="<?php echo esc_url($first_link); ?>" >
                                <?php if ($first_img): ?>
                                    <img src="<?php echo esc_url($first_img); ?>" alt="<?php echo esc_attr($first_title); ?>" >
                                <?php endif; ?>
                                    <div class="vote-hero__overlay" >
                                        <strong ><?php echo esc_html($first_title); ?></strong>
                                    
                                        <?php if ($first_content): ?>
                                            <p class="vote-hero__content"><?php echo esc_html($first_content); ?></p>
                                        <?php endif; ?>
                                    </div>
                            </a>
                        </div>

                        <div class="vote-hero__body" >
                            <div class="vote-hero__left" >
                                <div class="vote-hero_label">
                                <strong>投票項目</strong>
                                </div>
                                <div class="vote-hero__meta" >
                                    <?php if ($items_text): ?>
                                        <span class="vote-hero_label">投票項目：<?php echo esc_html($items_text); ?></span>
                                    <?php endif; ?>
                                    <?php if ($vote_dates_text): ?>
                                        <span class="vote-hero_time">統計時間：<?php echo esc_html($vote_dates_text); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="vote-hero__right">
                                <a href="<?php echo esc_url($first_link); ?>">
                                    來去投票
                                </a>
                            </div>
                        </div>
                        <div class="vote-hero__comments" >
                            <div class="vote-hero__comments_border">
                            </div>
                            <?php echo $comment_html; ?>
                        </div>
                    </article>
                <div class="more-votes">
                    <strong>更多創業市調</strong>
                </div>
        <!-- 2) 其他篇 -->
         <div class="vote-others-wrap">

        <?php while ($votes->have_posts()): $votes->the_post(); ?>
		        <?php
                        $id      = get_the_ID();
                        $title   = get_the_title();
                        $link    = get_the_permalink();
                        $img     = get_the_post_thumbnail_url($id, 'full');
                        $content = get_the_content();
                        //投票資訊
                        $vote_dates      = get_field('vote_dates');
                        error_log(print_r($vote_dates, true));
                        $vote_dates_text = date('Y/m/d', (int) ($vote_dates['stat_start'])) . ' ~ ' . date('Y/m/d', (int) ($vote_dates['stat_end']));
                        //留言
                        $comments = get_comments([
                            'post_id' => $id,
                            'number'  => 3,
                            'status'  => 'approve',
                            'orderby' => 'comment_date_gmt',
                            'order'   => 'DESC',
                        ]);
                        $comment_html = '';
                        if ($comments) {
                            foreach ($comments as $c) {
                                $author  = get_comment_author($c);
                                $excerpt = wp_html_excerpt(
                                    wp_strip_all_tags($c->comment_content),
                                    60,
                                    '…'
                                );
                                $selected_pair = get_comment_meta($c->comment_ID, 'selected_pair', true);
                                $comment_html .= '<div class="vote-comment">
		                                      <img src="' . plugin_dir_url(dirname(__FILE__)) . '/Asset/img/call_3.png' . '"
		                                           alt="' . esc_attr($author) . '"
		                                           width="30" height="30">
		                                      <strong><span style="color:#004BD0;">' . esc_html($author) . '</span> - ' . esc_html($selected_pair) . '</strong>： <strong>' . esc_html($excerpt) . '</strong>
		                                  </div>';
                            }
                        }
                    ?>
						<div class="vote-card">
						  <div class="vote-thumb">
                            <a href="<?php echo esc_url($link); ?>" >
						        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="投票主題代表圖片">
                            </a>
						  </div>

						  <div class="vote-main">
						    <div class="vote-header">
						      <div class="vote-header-content">
						        <div class="vote-dates"><?php echo $vote_dates_text; ?></div>
                                <a style="color:inherit;" href="<?php echo esc_url($link); ?>"  >
                                    <h3 class="vote-title"><?php echo $title; ?></h3>
                                </a>
						        <p class="vote-excerpt">
						          <?php echo $content; ?>
						        </p>
						      </div>
						      <div class="vote-cta">
						        <a class="vote-btn" href="<?php echo $link; ?>">來去投票</a>
						      </div>
						    </div>
                            <?php 
                            //判斷裝置是否為電腦
                            if (!wp_is_mobile()) {
                                echo '<div class="vote-comments">';
                                echo $comment_html;
                                echo '</div>';
                            } ?>
						    <!-- <div class="vote-comments">
						      
						    </div> -->
						  </div>
                          <?php 
                            //判斷裝置是否為平板以下
                            if (wp_is_mobile()) {
                                echo '<div class="vote-comments_mobile">';
                                echo $comment_html;
                                echo '</div>';
                            } ?>
						</div>
				<?php endwhile; ?>

        </div>

        <?php wp_reset_postdata();
            else:
                echo '<p>目前沒有市調。</p>';
            endif;
        ?>
      </div>
      <div class="col-lg-3">
        <?php get_template_part('brand', 'sidebar'); ?>
      </div>
    </div>
  </div>
</section>
<?php get_footer();

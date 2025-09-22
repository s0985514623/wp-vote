<?php
/**
 * 首頁ShortCode Class
 */

 namespace Ren\WpVote\Template;

 class ShortCode
 {
    use \J7\WpUtils\Traits\SingletonTrait;

    public function __construct()
    {
        add_shortcode('home_shortcode', [$this, 'shortcode_function'], 10, 1);
    }
    
    public function shortcode_function($atts)
    {
        // 取得市調列表
        $votes = new \WP_Query([
            'post_type'      => 'vote',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        //ob_start 應用
        $html = '';
        ob_start();
    ?>
    <style>
        .home-vote-wrap{
            background:linear-gradient(180deg, #3c3b4b 0%, #262533 100%);
            margin-bottom: 30px;
        }
        .home-vote-wrap-container{
            max-width:1200px;
            padding:18px 16px 48px;
            margin-inline:auto;
            color:#fff;
        }
        .home-vote-more{
            font-size: 16px;
            color: #fff;
            padding: 10px 0;
        }
        .wrap{
            display:grid;
            grid-template-columns: 270px 1fr;
            gap:24px;
            color:#fff;
        }

        /* 左側側欄 */
        .side .logo img{
            width:100%;
            height:100%;
        }

        /* 右側主區 */
        .main h2{
            margin:0 0 16px; font-size:28px; letter-spacing:.02em;
            display:flex; align-items:center; gap:10px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap: 16px;
        }

        .card-vote{
            overflow:hidden;
            display:flex;
            flex-direction:column;
            min-height: 585px;
            border-bottom:1px solid #e6e6e680;
        }
        .card-vote-img{
            width:100%; 
            height:100%; 
            object-fit:cover;
            display:block;
        }
        .card-vote figure{
            margin:0; 
            position:relative;
            overflow:hidden; 
            aspect-ratio: 500 / 327;
        }
        .card-vote .comment-item{
            font-size:13px;
            border-bottom:1px solid #e6e6e680;
            padding-bottom: 12px;
        }
        .comment-item-excerpt{
            overflow: hidden;            /* 超出範圍隱藏 */
            display: -webkit-box;        /* 設定為彈性盒子 */
            -webkit-line-clamp: 1;       /* 限制顯示的行數 (這裡是 1 行) */
            -webkit-box-orient: vertical;/* 設定盒子排列方向為垂直 */
            margin-bottom: 0;
        }
        .card-vote .title{
            position:absolute; left:0; right:0; bottom:0;
            padding:14px 16px;
            background: linear-gradient(180deg, rgba(0,0,0,0) 0, rgba(0,0,0,.55) 60%, rgba(0,0,0,.75) 100%);
            font-size:22px; 
            line-height:1.3; 
            font-weight:600;
        }


        .list{
            padding:16px 16px 0; margin:0; list-style:none;
            display:flex; flex-direction:column; gap:12px;
        }
        .list li{ color:#ddd; display:flex; gap:10px; align-items:flex-start}
        .bubble{
            width:18px; height:18px; flex:0 0 18px; margin-top:2px;
        }
        .actions{
            margin-top:auto; display:flex; gap:10px; padding:16px;
        }
        .btn{
            appearance:none; border:0; cursor:pointer;
            padding:10px 14px; border-radius:10px; font-weight:700;
            color:#111; background:#ddd; transition:.2s transform, .2s filter;
        }
        .btn:hover{filter:brightness(1.06); transform:translateY(-1px)}
        .btn.primary{ background:#ff6b00; color:#fff }
        .btn.ghost{ background:transparent; color:#fff; outline:1px solid #ffffff33 }
        .btn.alt{ background:#4e8ced; color:#fff }

        /* RWD */
        @media (max-width: 1100px){
            .wrap{grid-template-columns: 1fr}
            .side{position:relative; min-height: auto}
        }
        @media (max-width: 980px){
            .cards{grid-template-columns:repeat(2, 1fr)}
        }
        @media (max-width: 640px){
            .cards{grid-template-columns:1fr}
        }
    </style>
    <div class="home-vote-wrap">
        <div class="home-vote-wrap-container">
            <h3 class="home-vote-title" style="font-size:26px;font-weight:bold;margin-bottom:10px;">
                <img src="<?php bloginfo('template_directory'); ?>/img/viedo.png" width="35" height="25" >
                連合創業一起投
                <a href="<?php echo home_url('/vote'); ?>" class="home-vote-more pull-right" target="_blank">看更多</a>
            </h3>
            <div class="wrap">
                <!-- 左側：宣傳/側欄 -->
                <aside class="side">
                    <div class="logo">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/home_vote.png" alt="logo">
                    </div>
                </aside>

                <!-- 右側：主內容 -->
                <main class="main">
                    <section class="cards">
                    <?php
                    if ($votes->have_posts()):
                        while ($votes->have_posts()):
                            $votes->the_post();
                            $id = get_the_ID();
                            $title = get_the_title();
                            $link = get_the_permalink();
                            $img = get_the_post_thumbnail_url($id, 'full');
                            $comments = get_comments([
                                'post_id' => $id,
                                'number'  => 5,
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
                                        20,
                                        '…'
                                    );
                                    $selected_pair = get_comment_meta($c->comment_ID, 'selected_pair', true);
                                    if($excerpt!=''){
                                    $comment_html .= '<li class="comment-item">
                                        <img src="' . plugin_dir_url(dirname(__FILE__)) . '/Asset/img/call_3.png' . '"
						                                 alt="' . esc_attr($author) . '"
						                                 width="20" height="20">
                                        <div>
                                        <strong><span style="color:#004BD0;">' . esc_html($author) . '</span> - ' . esc_html($selected_pair) . '</strong>：
                                            <p class="comment-item-excerpt">' . esc_html($excerpt) . '</p>
                                            </div>
                                            </li>';
                                    }
                                }
                            }
                            ?>
                            <!-- 卡片 1 -->
                            <article class="card-vote">
                                <figure>
                                <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" class="card-vote-img">
                                <figcaption class="title"><a style="color:#fff;" href="<?php echo $link; ?>"><?php echo $title; ?></a></figcaption>
                                </figure>

                                <ul class="list">
                                <?php echo $comment_html; ?>
                                </ul>
                                <div class="actions">
                                    <a href="<?php echo $link; ?>" class="btn primary">前往投票</a>
                                    <a href="<?php echo $link; ?>" class="btn ghost">投票結果</a>
                                </div>
                            </article>
                               
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                    

                </main>
            </div>
        </div>
    </div>
    
    <?php
        $html .= ob_get_clean();
        return $html;
    }
    
    
 }
 ?>
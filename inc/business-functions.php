<?php
/**
 * 事業部表示用のWordPress関数
 * 株式会社デュオコミュニケーションズ
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 事業部一覧を取得する関数
 */
function get_business_divisions($limit = -1) {
    $args = array(
        'post_type' => 'business_divisions',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_key' => '_display_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * 事業部データを取得してElementor用のHTMLを出力
 */
function display_business_divisions_for_elementor() {
    $business_query = get_business_divisions();
    $output = '';
    
    if ($business_query->have_posts()) {
        $output .= '<div class="business-grid">';
        
        while ($business_query->have_posts()) {
            $business_query->the_post();
            $post_id = get_the_ID();
            $abbreviation = get_post_meta($post_id, '_abbreviation', true);
            $overview = get_post_meta($post_id, '_overview', true);
            $link_url = get_post_meta($post_id, '_link_url', true);
            $hover_image = get_post_meta($post_id, '_hover_image', true);
            
            // デフォルトリンク（カスタムURLが設定されていない場合）
            if (empty($link_url)) {
                $link_url = get_permalink($post_id);
            }
            
            $output .= '<div class="business-card" data-bg="' . esc_url($hover_image) . '" onclick="location.href=\'' . esc_url($link_url) . '\'">';
            $output .= '<h4>' . esc_html($abbreviation) . '</h4>';
            $output .= '<p class="subtitle">' . esc_html(get_the_title()) . '</p>';
            $output .= '<p>' . esc_html($overview) . '</p>';
            $output .= '</div>';
        }
        
        // サービス一覧ボタン
        $service_archive_link = get_post_type_archive_link('business_divisions');
        if (!$service_archive_link) {
            $service_archive_link = home_url('/service/'); // フォールバック
        }
        
        $output .= '<div class="service-button">';
        $output .= '<a href="' . esc_url($service_archive_link) . '" class="btn-primary">サービス一覧を見る</a>';
        $output .= '</div>';
        
        $output .= '</div>';
        
        wp_reset_postdata();
    } else {
        $output .= '<p class="no-business">事業部情報が見つかりません。</p>';
    }
    
    return $output;
}

/**
 * 事業部用ショートコード
 */
function business_divisions_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
        'layout' => 'grid'
    ), $atts);
    
    return display_business_divisions_for_elementor();
}
add_shortcode('business_divisions', 'business_divisions_shortcode');

/**
 * 求人一覧を取得する関数
 */
function get_job_postings($limit = -1, $status = 'all') {
    $args = array(
        'post_type' => 'job_postings',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    if ($status !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => '_recruitment_status',
                'value' => $status,
                'compare' => '='
            )
        );
    }
    
    return new WP_Query($args);
}

/**
 * 求人一覧をElementor用に出力
 */
function display_job_postings_for_elementor($limit = -1) {
    $job_query = get_job_postings($limit, '募集中');
    $output = '';
    
    if ($job_query->have_posts()) {
        $output .= '<div class="job-listings">';
        
        while ($job_query->have_posts()) {
            $job_query->the_post();
            $post_id = get_the_ID();
            $job_category = get_post_meta($post_id, '_job_category', true);
            $employment_type = get_post_meta($post_id, '_employment_type', true);
            $salary = get_post_meta($post_id, '_salary', true);
            $location = get_post_meta($post_id, '_location', true);
            
            $output .= '<div class="job-item" onclick="location.href=\'' . esc_url(get_permalink()) . '\'">';
            $output .= '<div class="job-header">';
            $output .= '<h3 class="job-title">' . esc_html(get_the_title()) . '</h3>';
            $output .= '<div class="job-meta">';
            if ($job_category) {
                $output .= '<span class="job-category">' . esc_html($job_category) . '</span>';
            }
            if ($employment_type) {
                $output .= '<span class="employment-type">' . esc_html($employment_type) . '</span>';
            }
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="job-details">';
            if ($salary) {
                $output .= '<p><strong>給与:</strong> ' . esc_html($salary) . '</p>';
            }
            if ($location) {
                $output .= '<p><strong>勤務地:</strong> ' . esc_html($location) . '</p>';
            }
            $excerpt = get_the_excerpt();
            if ($excerpt) {
                $output .= '<p class="job-excerpt">' . esc_html($excerpt) . '</p>';
            }
            $output .= '</div>';
            $output .= '<div class="job-actions">';
            $output .= '<a href="' . esc_url(get_permalink()) . '" class="job-detail-btn">詳細を見る</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        wp_reset_postdata();
    } else {
        $output .= '<p class="no-jobs">現在募集中の求人はございません。</p>';
    }
    
    return $output;
}

/**
 * 求人用ショートコード
 */
function job_postings_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
        'status' => '募集中'
    ), $atts);
    
    return display_job_postings_for_elementor($atts['limit']);
}
add_shortcode('job_postings', 'job_postings_shortcode');

/**
 * 最新ニュース（ブログ投稿）を取得
 */
function get_latest_news($limit = 4) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * 最新ニュースをElementor用に出力
 */
function display_latest_news_for_elementor($limit = 4) {
    $news_query = get_latest_news($limit);
    $output = '';
    
    if ($news_query->have_posts()) {
        $output .= '<div class="news-grid">';
        
        while ($news_query->have_posts()) {
            $news_query->the_post();
            $output .= '<div class="news-item" onclick="location.href=\'' . esc_url(get_permalink()) . '\'">';
            $output .= '<div class="news-date">' . get_the_date('Y.m.d') . '</div>';
            $output .= '<div class="news-title">' . esc_html(get_the_title()) . '</div>';
            $excerpt = get_the_excerpt();
            if ($excerpt) {
                $output .= '<div class="news-excerpt">' . esc_html($excerpt) . '</div>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        // もっと見るボタン
        $blog_page_url = get_permalink(get_option('page_for_posts'));
        if (!$blog_page_url) {
            $blog_page_url = home_url('/blog/'); // フォールバック
        }
        
        $output .= '<div class="more-news-btn">';
        $output .= '<a href="' . esc_url($blog_page_url) . '" class="btn-primary">もっと見る</a>';
        $output .= '</div>';
        
        wp_reset_postdata();
    } else {
        $output .= '<p class="no-news">お知らせはありません。</p>';
    }
    
    return $output;
}

/**
 * 最新ニュース用ショートコード
 */
function latest_news_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 4
    ), $atts);
    
    return display_latest_news_for_elementor($atts['limit']);
}
add_shortcode('latest_news', 'latest_news_shortcode');

/**
 * 事業部詳細ページでの関連投稿を取得
 */
function get_related_business_posts($current_post_id, $limit = 3) {
    $args = array(
        'post_type' => 'business_divisions',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'post__not_in' => array($current_post_id),
        'orderby' => 'rand'
    );
    
    return new WP_Query($args);
}

/**
 * パンくずリストを生成
 */
function duo_communications_breadcrumbs() {
    $output = '<nav class="breadcrumbs" aria-label="breadcrumb">';
    $output .= '<ol class="breadcrumb-list">';
    $output .= '<li class="breadcrumb-item"><a href="' . esc_url(home_url()) . '">ホーム</a></li>';
    
    if (is_post_type_archive('business_divisions')) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">事業情報</li>';
    } elseif (is_singular('business_divisions')) {
        $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('business_divisions')) . '">事業情報</a></li>';
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_post_type_archive('job_postings')) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">採用情報</li>';
    } elseif (is_singular('job_postings')) {
        $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('job_postings')) . '">採用情報</a></li>';
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_page()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_single()) {
        $blog_page_url = get_permalink(get_option('page_for_posts'));
        if ($blog_page_url) {
            $output .= '<li class="breadcrumb-item"><a href="' . esc_url($blog_page_url) . '">ブログ</a></li>';
        }
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_home()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">ブログ</li>';
    }
    
    $output .= '</ol>';
    $output .= '</nav>';
    return $output;
}

/**
 * パンくずリスト用ショートコード
 */
function breadcrumbs_shortcode() {
    return duo_communications_breadcrumbs();
}
add_shortcode('breadcrumbs', 'breadcrumbs_shortcode');

/**
 * AJAX処理：動的な事業部読み込み
 */
function ajax_load_business_divisions() {
    // nonce検証
    if (!wp_verify_nonce($_POST['nonce'], 'duo_nonce')) {
        wp_die('セキュリティチェックに失敗しました');
    }
    
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : -1;
    $output = display_business_divisions_for_elementor();
    
    wp_send_json_success($output);
}
add_action('wp_ajax_load_business_divisions', 'ajax_load_business_divisions');
add_action('wp_ajax_nopriv_load_business_divisions', 'ajax_load_business_divisions');

/**
 * AJAX処理：動的な求人読み込み
 */
function ajax_load_job_postings() {
    // nonce検証
    if (!wp_verify_nonce($_POST['nonce'], 'duo_nonce')) {
        wp_die('セキュリティチェックに失敗しました');
    }
    
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : -1;
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '募集中';
    
    $output = display_job_postings_for_elementor($limit);
    
    wp_send_json_success($output);
}
add_action('wp_ajax_load_job_postings', 'ajax_load_job_postings');
add_action('wp_ajax_nopriv_load_job_postings', 'ajax_load_job_postings');

/**
 * AJAX処理：動的なニュース読み込み
 */
function ajax_load_latest_news() {
    // nonce検証
    if (!wp_verify_nonce($_POST['nonce'], 'duo_nonce')) {
        wp_die('セキュリティチェックに失敗しました');
    }
    
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 4;
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'offset' => $offset,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $news_query = new WP_Query($args);
    $output = '';
    
    if ($news_query->have_posts()) {
        while ($news_query->have_posts()) {
            $news_query->the_post();
            $output .= '<div class="news-item" onclick="location.href=\'' . esc_url(get_permalink()) . '\'">';
            $output .= '<div class="news-date">' . get_the_date('Y.m.d') . '</div>';
            $output .= '<div class="news-title">' . esc_html(get_the_title()) . '</div>';
            $excerpt = get_the_excerpt();
            if ($excerpt) {
                $output .= '<div class="news-excerpt">' . esc_html($excerpt) . '</div>';
            }
            $output .= '</div>';
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success($output);
}
add_action('wp_ajax_load_latest_news', 'ajax_load_latest_news');
add_action('wp_ajax_nopriv_load_latest_news', 'ajax_load_latest_news');

/**
 * カスタム投稿タイプのアーカイブページタイトル設定
 */
function duo_communications_archive_titles($title) {
    if (is_post_type_archive('business_divisions')) {
        return 'Service-事業情報-';
    } elseif (is_post_type_archive('job_postings')) {
        return 'Recruitment-採用情報-';
    }
    return $title;
}
add_filter('get_the_archive_title', 'duo_communications_archive_titles');
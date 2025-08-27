<?php
/**
 * Astra Child Theme functions
 * 株式会社デュオコミュニケーションズ
 * 
 * このファイルは子テーマの全機能を管理します
 * Author: Web Developer
 * Version: 1.0.0
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

// 子テーマのバージョン
define('DUO_THEME_VERSION', '1.0.1');

// デバッグモードの設定（開発時のみ）
define('DUO_DEBUG_MODE', defined('WP_DEBUG') && WP_DEBUG);

/* ========================================
   基本スタイルシート読み込み
======================================== */

/**
 * 子テーマのスタイルを読み込み
 */
function astra_child_enqueue_styles() {
    wp_enqueue_style(
        'astra-child-theme-css', 
        get_stylesheet_directory_uri() . '/style.css', 
        array('astra-theme-css'), 
        DUO_THEME_VERSION
    );
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles', 15);

/**
 * 追加CSSファイルの読み込み
 */
function duo_communications_additional_styles() {
    // デバッグモードでCSSファイルの存在確認
    if (DUO_DEBUG_MODE && current_user_can('administrator')) {
        $css_files = array(
            'main.css',
            'animations.css', 
            'responsive.css',
            'admin.css'
        );
        
        foreach ($css_files as $file) {
            $file_path = get_stylesheet_directory() . '/assets/css/minified/' . $file;
            if (!file_exists($file_path)) {
                error_log('DUO_THEME: CSS file not found - ' . $file_path);
            }
        }
    }
    // メインCSS - 優先度を上げる
    wp_enqueue_style(
        'duo-main-css',
        get_stylesheet_directory_uri() . '/assets/css/minified/main.css',
        array('astra-child-theme-css'),
        DUO_THEME_VERSION
    );
    
    // アニメーションCSS
    wp_enqueue_style(
        'duo-animations-css',
        get_stylesheet_directory_uri() . '/assets/css/minified/animations.css',
        array('duo-main-css'),
        DUO_THEME_VERSION
    );
    
    // レスポンシブCSS
    wp_enqueue_style(
        'duo-responsive-css',
        get_stylesheet_directory_uri() . '/assets/css/minified/responsive.css',
        array('duo-main-css'),
        DUO_THEME_VERSION
    );
    
    // 管理画面でのみ管理用CSSを読み込み
    if (is_admin()) {
        wp_enqueue_style(
            'duo-admin-css',
            get_stylesheet_directory_uri() . '/assets/css/minified/admin.css',
            array(),
            DUO_THEME_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'duo_communications_additional_styles', 25);

/* ========================================
   Google Fontsの読み込み
======================================== */

/**
 * Google Fonts の読み込み
 */
function duo_communications_google_fonts() {
    $font_families = array(
        'Noto+Sans+JP:wght@300;400;500;600;700',
        'Inter:wght@300;400;500;600;700'
    );
    
    $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $font_families) . '&display=swap';
    
    wp_enqueue_style(
        'duo-google-fonts',
        $fonts_url,
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'duo_communications_google_fonts', 5);

/* ========================================
   SEO・パフォーマンス最適化
======================================== */

/**
 * DNS prefetch for external resources
 */
function duo_communications_dns_prefetch() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'duo_communications_dns_prefetch', 1);

/**
 * カスタムメタタグの追加
 */
function duo_communications_custom_meta_tags() {
    // viewport設定の最適化
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">' . "\n";
    
    // OGP設定
    if (is_front_page()) {
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:site_name" content="株式会社デュオコミュニケーションズ">' . "\n";
        echo '<meta property="og:title" content="株式会社デュオコミュニケーションズ - 営業会社">' . "\n";
        echo '<meta property="og:description" content="株式会社デュオコミュニケーションズは、toB直販を中心とした営業会社です。新規採用強化、問い合わせ増加、企業信頼性向上を目指します。">' . "\n";
        echo '<meta property="og:url" content="' . home_url() . '">' . "\n";
        
        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="株式会社デュオコミュニケーションズ - 営業会社">' . "\n";
        echo '<meta name="twitter:description" content="株式会社デュオコミュニケーションズは、toB直販を中心とした営業会社です。">' . "\n";
    }
}
add_action('wp_head', 'duo_communications_custom_meta_tags', 2);

/* ========================================
   ショートコード機能
======================================== */

/**
 * 会社情報表示ショートコード
 */
function duo_company_info_shortcode($atts) {
    $atts = shortcode_atts(array(
        'field' => 'name'
    ), $atts);
    
    $company_info = array(
        'name' => '株式会社デュオコミュニケーションズ',
        'established' => '2023年6月2日',
        'employees' => '約30名',
        'business' => '営業会社（toB直販メイン）',
        'address' => '東京都港区南青山3-1-36 青山丸竹ビル 6F'
    );
    
    return isset($company_info[$atts['field']]) ? esc_html($company_info[$atts['field']]) : '';
}
add_shortcode('company_info', 'duo_company_info_shortcode');

/**
 * 現在年度表示ショートコード
 */
function duo_current_year_shortcode() {
    return date('Y');
}
add_shortcode('current_year', 'duo_current_year_shortcode');

/**
 * ボタン生成ショートコード
 */
function duo_button_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'url' => '#',
        'type' => 'primary',
        'target' => '_self',
        'class' => ''
    ), $atts);
    
    $classes = 'btn-' . $atts['type'] . ' ' . $atts['class'];
    
    return sprintf(
        '<a href="%s" class="%s" target="%s">%s</a>',
        esc_url($atts['url']),
        esc_attr($classes),
        esc_attr($atts['target']),
        $content ? esc_html($content) : 'ボタン'
    );
}
add_shortcode('duo_button', 'duo_button_shortcode');

/* ========================================
   セキュリティ機能
======================================== */

/**
 * ファイルアップロード制限
 */
function duo_communications_upload_security($file) {
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx');
    $file_type = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    if (!in_array(strtolower($file_type), $allowed_types)) {
        $file['error'] = 'このファイルタイプはアップロードできません。';
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'duo_communications_upload_security');

/* ========================================
   パフォーマンス最適化
======================================== */

/**
 * 不要なスクリプト・スタイルの削除
 */
function duo_communications_cleanup_wp_head() {
    // 絵文字関連のスクリプト削除
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Windows Live Writer削除
    remove_action('wp_head', 'wlwmanifest_link');
    
    // RSDリンク削除
    remove_action('wp_head', 'rsd_link');
    
    // 短縮URLリンク削除
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'duo_communications_cleanup_wp_head');

/**
 * データベースクエリの最適化
 */
function duo_communications_optimize_queries() {
    // 投稿リビジョンの制限
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 3);
    }
    
    // 自動保存間隔の調整
    if (!defined('AUTOSAVE_INTERVAL')) {
        define('AUTOSAVE_INTERVAL', 300); // 5分
    }
    
    // ゴミ箱の自動削除期間
    if (!defined('EMPTY_TRASH_DAYS')) {
        define('EMPTY_TRASH_DAYS', 7); // 7日
    }
}
add_action('init', 'duo_communications_optimize_queries');

/* ========================================
   カスタム機能ファイルの読み込み
======================================== */

$inc_files = array(
    'inc/custom-post-types.php',      // カスタム投稿タイプ
    'inc/customizer.php',             // カスタマイザー設定  
    'inc/business-functions.php',     // 事業部表示関数
    'inc/theme-setup.php'             // テーマ基本設定
);

foreach ($inc_files as $file) {
    $filepath = get_stylesheet_directory() . '/' . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    } else {
        // ファイルが見つからない場合の警告（管理者にのみ表示）
        if (current_user_can('manage_options') && DUO_DEBUG_MODE) {
            add_action('admin_notices', function() use ($file) {
                echo '<div class="notice notice-error"><p><strong>Duo Communications:</strong> File not found: ' . esc_html($file) . '</p></div>';
            });
        }
        
        // ログにエラーを記録
        error_log('Duo Communications Theme: Missing file - ' . $file);
    }
}

/* ========================================
   管理画面カスタマイズ
======================================== */

/**
 * 管理画面ダッシュボードのカスタマイズ
 */
function duo_communications_custom_dashboard_widgets() {
    wp_add_dashboard_widget(
        'duo_site_stats',
        '株式会社デュオコミュニケーションズ - サイト統計',
        'duo_display_site_stats_widget'
    );
}
add_action('wp_dashboard_setup', 'duo_communications_custom_dashboard_widgets');

/**
 * カスタムダッシュボードウィジェットの内容
 */
function duo_display_site_stats_widget() {
    $business_count = wp_count_posts('business_divisions')->publish;
    $job_count = wp_count_posts('job_postings')->publish;
    $post_count = wp_count_posts()->publish;
    $page_count = wp_count_posts('page')->publish;
    
    echo '<div class="duo-dashboard-widget">';
    echo '<div class="duo-stats-grid">';
    
    echo '<div class="duo-stat-item">';
    echo '<span class="duo-stat-number">' . $business_count . '</span>';
    echo '<span class="duo-stat-label">事業部</span>';
    echo '</div>';
    
    echo '<div class="duo-stat-item">';
    echo '<span class="duo-stat-number">' . $job_count . '</span>';
    echo '<span class="duo-stat-label">求人</span>';
    echo '</div>';
    
    echo '<div class="duo-stat-item">';
    echo '<span class="duo-stat-number">' . $post_count . '</span>';
    echo '<span class="duo-stat-label">記事</span>';
    echo '</div>';
    
    echo '<div class="duo-stat-item">';
    echo '<span class="duo-stat-number">' . $page_count . '</span>';
    echo '<span class="duo-stat-label">ページ</span>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}

/* ========================================
   エラーハンドリングとログ機能
======================================== */

/**
 * カスタムエラーログ機能
 */
function duo_log_error($message, $level = 'ERROR') {
    if (DUO_DEBUG_MODE) {
        $log_message = sprintf(
            '[%s] [%s] %s',
            date('Y-m-d H:i:s'),
            $level,
            $message
        );
        error_log('DUO_THEME: ' . $log_message);
    }
}

/* ========================================
   多言語対応準備
======================================== */

/**
 * 多言語化準備
 */
function duo_communications_load_textdomain() {
    load_child_theme_textdomain('duo-communications', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'duo_communications_load_textdomain');

/* ========================================
   デバッグ・開発者向け機能
======================================== */

/**
 * 開発者向けデバッグ情報表示
 */
function duo_display_debug_info() {
    if (DUO_DEBUG_MODE && current_user_can('administrator')) {
        add_action('wp_footer', function() {
            global $wpdb;
            $num_queries = $wpdb->num_queries;
            $page_load_time = timer_stop(0);
            
            echo '<div id="duo-debug-info" style="position:fixed;bottom:0;left:0;background:#000;color:#fff;padding:10px;font-size:12px;z-index:9999;">';
            echo 'Queries: ' . $num_queries . ' | Load Time: ' . $page_load_time . 's | PHP Memory: ' . size_format(memory_get_peak_usage(true));
            echo '</div>';
        });
    }
}
add_action('init', 'duo_display_debug_info');

/**
 * テーマの初期化完了ログ
 */
if (DUO_DEBUG_MODE) {
    duo_log_error('Duo Communications Theme initialized successfully', 'INFO');
}

// テーマアクティベート時の初期設定
register_activation_hook(__FILE__, function() {
    // リライトルールをフラッシュ
    flush_rewrite_rules();
    
    // 初期設定の保存
    update_option('duo_theme_version', DUO_THEME_VERSION);
    update_option('duo_theme_activated', current_time('mysql'));
});

// テーマ切り替え時のクリーンアップ
add_action('switch_theme', function() {
    // カスタムオプションをクリーンアップ
    delete_option('duo_theme_version');
    delete_option('duo_theme_activated');
});

?>
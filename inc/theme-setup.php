<?php
/**
 * テーマ基本設定
 * 株式会社デュオコミュニケーションズ
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマサポートの追加
 */
function duo_communications_theme_support() {
    // 投稿サムネイルのサポート
    add_theme_support('post-thumbnails');
    
    // カスタムロゴのサポート
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // ページタイトルのサポート
    add_theme_support('title-tag');
    
    // HTML5サポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style'
    ));
    
    // Elementorサポート
    add_theme_support('elementor');
    
    // WooCommerceサポート（将来的に使用する可能性がある場合）
    add_theme_support('woocommerce');
    
    // レスポンシブ埋め込みサポート
    add_theme_support('responsive-embeds');
    
    // エディタースタイルサポート
    add_theme_support('editor-styles');
    add_editor_style();
    
    // アライン（画像の配置）サポート
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'duo_communications_theme_support');

/**
 * メニュー位置を登録
 */
function duo_communications_register_menus() {
    register_nav_menus(array(
        'primary' => 'メインナビゲーション',
        'footer' => 'フッターメニュー',
        'mobile' => 'モバイルメニュー'
    ));
}
add_action('init', 'duo_communications_register_menus');

/**
 * ウィジェットエリアを追加
 */
function duo_communications_widgets_init() {
    register_sidebar(array(
        'name' => 'フッター左',
        'id' => 'footer-left',
        'description' => 'フッターの左側エリア',
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'フッター右',
        'id' => 'footer-right',
        'description' => 'フッターの右側エリア',
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'サイドバー',
        'id' => 'sidebar-1',
        'description' => 'メインサイドバー',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'duo_communications_widgets_init');

/**
 * JavaScript・CSSファイルの読み込み
 */
function duo_communications_enqueue_scripts() {
    // メインのJavaScriptファイル
    wp_enqueue_script(
        'duo-main-js',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        DUO_THEME_VERSION,
        true
    );
    
    // メインのCSSファイル
    wp_enqueue_style(
        'duo-main-css',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        array('astra-theme-css'),
        DUO_THEME_VERSION
    );
    
    // アニメーション用CSS
    wp_enqueue_style(
        'duo-animations-css',
        get_stylesheet_directory_uri() . '/assets/css/animations.css',
        array(),
        DUO_THEME_VERSION
    );
    
    // コメント返信用スクリプト（単一記事ページでコメントが有効な場合）
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // AJAXのためのローカライズ
    wp_localize_script('duo-main-js', 'duo_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('duo_nonce'),
        'home_url' => home_url(),
        'theme_url' => get_stylesheet_directory_uri()
    ));
}
add_action('wp_enqueue_scripts', 'duo_communications_enqueue_scripts');

/**
 * 管理画面用CSS・JS
 */
function duo_communications_admin_enqueue_scripts() {
    wp_enqueue_style(
        'duo-admin-css',
        get_stylesheet_directory_uri() . '/assets/css/admin.css',
        array(),
        DUO_THEME_VERSION
    );
    
    wp_enqueue_script(
        'duo-admin-js',
        get_stylesheet_directory_uri() . '/assets/js/admin.js',
        array('jquery'),
        DUO_THEME_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'duo_communications_admin_enqueue_scripts');

/**
 * カスタム画像サイズの追加
 */
function duo_communications_custom_image_sizes() {
    add_image_size('business-card', 400, 300, true);      // 事業部カード用
    add_image_size('news-thumbnail', 300, 200, true);     // ニュースサムネイル用
    add_image_size('hero-image', 1920, 1080, true);       // ヒーローイメージ用
    add_image_size('job-thumbnail', 250, 150, true);      // 求人サムネイル用
}
add_action('after_setup_theme', 'duo_communications_custom_image_sizes');

/**
 * 画像サイズの選択肢に追加
 */
function duo_communications_custom_sizes($sizes) {
    return array_merge($sizes, array(
        'business-card' => '事業部カード',
        'news-thumbnail' => 'ニュースサムネイル',
        'hero-image' => 'ヒーローイメージ',
        'job-thumbnail' => '求人サムネイル'
    ));
}
add_filter('image_size_names_choose', 'duo_communications_custom_sizes');

/**
 * 抜粋の長さをカスタマイズ
 */
function duo_communications_excerpt_length($length) {
    if (is_admin()) {
        return $length;
    }
    
    // 投稿タイプ別の抜粋の長さ
    if (get_post_type() === 'job_postings') {
        return 30;
    } elseif (get_post_type() === 'business_divisions') {
        return 20;
    }
    
    return 25;
}
add_filter('excerpt_length', 'duo_communications_excerpt_length');

/**
 * 抜粋の「続きを読む」をカスタマイズ
 */
function duo_communications_excerpt_more($more) {
    if (is_admin()) {
        return $more;
    }
    
    return '...';
}
add_filter('excerpt_more', 'duo_communications_excerpt_more');

/**
 * コンタクトフォーム7のスタイル調整
 */
function duo_communications_wpcf7_enqueue_styles() {
    if (function_exists('wpcf7_enqueue_styles')) {
        wp_dequeue_style('contact-form-7');
        wp_enqueue_style(
            'duo-contact-form-7',
            get_stylesheet_directory_uri() . '/assets/css/contact-form-7.css',
            array(),
            DUO_THEME_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'duo_communications_wpcf7_enqueue_styles', 20);

/**
 * セキュリティ強化：WordPressバージョンの非表示
 */
remove_action('wp_head', 'wp_generator');

/**
 * セキュリティ強化：RSSフィードからWordPressバージョン情報を削除
 */
function duo_communications_remove_version() {
    return '';
}
add_filter('the_generator', 'duo_communications_remove_version');

/**
 * セキュリティ強化：XMLRPCを無効化
 */
function duo_communications_disable_xmlrpc($methods) {
    return array();
}
add_filter('xmlrpc_methods', 'duo_communications_disable_xmlrpc');

/**
 * セキュリティ強化：ログイン画面でのエラーメッセージを一般化
 */
function duo_communications_login_errors() {
    return 'ログイン情報が正しくありません。';
}
add_filter('login_errors', 'duo_communications_login_errors');

/**
 * 管理画面のメニューカスタマイズ
 */
function duo_communications_admin_menu_customize() {
    // 管理者以外にはWordPress更新通知を非表示
    if (!current_user_can('administrator')) {
        remove_action('admin_notices', 'update_nag', 3);
    }
}
add_action('admin_menu', 'duo_communications_admin_menu_customize');

/**
 * 管理画面のフッターテキストをカスタマイズ
 */
function duo_communications_admin_footer_text($footer_text) {
    return sprintf(
        '%s | サイト制作：<a href="%s" target="_blank">株式会社デュオコミュニケーションズ</a>',
        $footer_text,
        home_url()
    );
}
add_filter('admin_footer_text', 'duo_communications_admin_footer_text');

/**
 * カスタム投稿タイプの管理画面カラム追加
 */
function duo_communications_business_divisions_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['abbreviation'] = '略称';
    $new_columns['display_order'] = '表示順';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_business_divisions_posts_columns', 'duo_communications_business_divisions_columns');

/**
 * カスタム投稿タイプの管理画面カラム内容
 */
function duo_communications_business_divisions_custom_column($column, $post_id) {
    switch ($column) {
        case 'abbreviation':
            echo esc_html(get_post_meta($post_id, '_abbreviation', true));
            break;
        case 'display_order':
            echo esc_html(get_post_meta($post_id, '_display_order', true));
            break;
    }
}
add_action('manage_business_divisions_posts_custom_column', 'duo_communications_business_divisions_custom_column', 10, 2);

/**
 * 求人投稿の管理画面カラム追加
 */
function duo_communications_job_postings_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['job_category'] = '職種';
    $new_columns['employment_type'] = '雇用形態';
    $new_columns['recruitment_status'] = '募集状況';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_job_postings_posts_columns', 'duo_communications_job_postings_columns');

/**
 * 求人投稿の管理画面カラム内容
 */
function duo_communications_job_postings_custom_column($column, $post_id) {
    switch ($column) {
        case 'job_category':
            echo esc_html(get_post_meta($post_id, '_job_category', true));
            break;
        case 'employment_type':
            echo esc_html(get_post_meta($post_id, '_employment_type', true));
            break;
        case 'recruitment_status':
            $status = get_post_meta($post_id, '_recruitment_status', true);
            $class = ($status === '募集中') ? 'active' : 'inactive';
            echo '<span class="recruitment-status ' . esc_attr($class) . '">' . esc_html($status) . '</span>';
            break;
    }
}
add_action('manage_job_postings_posts_custom_column', 'duo_communications_job_postings_custom_column', 10, 2);

/**
 * カスタム投稿タイプのソート機能を追加
 */
function duo_communications_sortable_columns($columns) {
    $columns['display_order'] = 'display_order';
    $columns['recruitment_status'] = 'recruitment_status';
    return $columns;
}
add_filter('manage_edit-business_divisions_sortable_columns', 'duo_communications_sortable_columns');
add_filter('manage_edit-job_postings_sortable_columns', 'duo_communications_sortable_columns');

/**
 * カスタム投稿タイプのソート処理
 */
function duo_communications_posts_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ($orderby === 'display_order') {
        $query->set('meta_key', '_display_order');
        $query->set('orderby', 'meta_value_num');
    } elseif ($orderby === 'recruitment_status') {
        $query->set('meta_key', '_recruitment_status');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'duo_communications_posts_orderby');

/**
 * 管理画面用CSS追加
 */
function duo_communications_admin_head() {
    ?>
    <style>
    .recruitment-status.active {
        color: #46b450;
        font-weight: bold;
    }
    .recruitment-status.inactive {
        color: #dc3232;
    }
    .business_divisions_page_business-settings,
    .job_postings_page_job-settings {
        background: #f1f1f1;
    }
    </style>
    <?php
}
add_action('admin_head', 'duo_communications_admin_head');

/**
 * Elementorとの互換性設定
 */
function duo_communications_elementor_compatibility() {
    // Elementorのテーマビルダーサポート
    if (defined('ELEMENTOR_VERSION')) {
        add_theme_support('elementor');
    }
}
add_action('after_setup_theme', 'duo_communications_elementor_compatibility');

/**
 * 投稿とページの並び順をカスタマイズ
 */
function duo_communications_pre_get_posts($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // 事業部の表示順序
    if (is_post_type_archive('business_divisions')) {
        $query->set('meta_key', '_display_order');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
    }
    
    // 求人の表示順序（募集中を優先、その後は新しい順）
    if (is_post_type_archive('job_postings')) {
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key' => '_recruitment_status',
                'value' => '募集中',
                'compare' => '='
            ),
            array(
                'key' => '_recruitment_status',
                'value' => '募集停止',
                'compare' => '='
            )
        ));
        $query->set('orderby', array(
            'meta_value' => 'ASC',
            'date' => 'DESC'
        ));
        $query->set('meta_key', '_recruitment_status');
    }
}
add_action('pre_get_posts', 'duo_communications_pre_get_posts');

/**
 * 検索結果からカスタム投稿タイプを除外
 */
function duo_communications_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // 管理投稿タイプは検索から除外
        $query->set('post_type', array('post', 'page'));
    }
}
add_action('pre_get_posts', 'duo_communications_search_filter');

/**
 * カスタム投稿タイプのパーマリンク設定
 */
function duo_communications_rewrite_rules() {
    // 事業部の詳細ページ
    add_rewrite_rule(
        '^business/([^/]+)/?$',
        'index.php?business_divisions=$matches[1]',
        'top'
    );
    
    // 求人の詳細ページ
    add_rewrite_rule(
        '^jobs/([^/]+)/?$',
        'index.php?job_postings=$matches[1]',
        'top'
    );
}
add_action('init', 'duo_communications_rewrite_rules');

/**
 * 初期化時の処理
 */
function duo_communications_init() {
    // 画像の最適化設定
    add_filter('jpeg_quality', function($quality, $context) {
        return ($context === 'edit_image') ? 90 : 85;
    }, 10, 2);
    
    // 画像アップロード時の自動リサイズ
    add_filter('big_image_size_threshold', function($threshold) {
        return 2048; // 2048px以上の画像を自動リサイズ
    });
}
add_action('init', 'duo_communications_init');

/**
 * クリーンアップ：不要なWordPressヘッダー情報を削除
 */
function duo_communications_cleanup_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
}
add_action('init', 'duo_communications_cleanup_head');
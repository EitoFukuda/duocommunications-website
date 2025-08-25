<?php
/**
 * Astra Child Theme functions
 * 株式会社デュオコミュニケーションズ
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

// 子テーマのバージョン
define('DUO_THEME_VERSION', '1.0.0');

// 子テーマのスタイルを読み込み
function astra_child_enqueue_styles() {
    wp_enqueue_style(
        'astra-child-theme-css', 
        get_stylesheet_directory_uri() . '/style.css', 
        array('astra-theme-css'), 
        DUO_THEME_VERSION
    );
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles', 15);

// カスタム機能ファイルを読み込み
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
        if (current_user_can('manage_options')) {
            add_action('admin_notices', function() use ($file) {
                echo '<div class="notice notice-error"><p>File not found: ' . esc_html($file) . '</p></div>';
            });
        }
    }
}
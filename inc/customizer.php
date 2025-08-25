<?php
/**
 * Astraカスタマイザー設定
 * 株式会社デュオコミュニケーションズ
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

/**
 * カスタマイザー設定の追加
 */
function duo_communications_customize_register($wp_customize) {
    
    // カラー設定セクション
    $wp_customize->add_section('duo_colors', array(
        'title' => 'デュオコミュニケーションズ カラー設定',
        'priority' => 30,
    ));

    // プライマリーカラー（ブラック）
    $wp_customize->add_setting('duo_primary_color', array(
        'default' => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'duo_primary_color', array(
        'label' => 'プライマリーカラー（ブラック）',
        'section' => 'duo_colors',
        'settings' => 'duo_primary_color',
    )));

    // セカンダリーカラー（ダークグレー）
    $wp_customize->add_setting('duo_secondary_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'duo_secondary_color', array(
        'label' => 'セカンダリーカラー（ダークグレー）',
        'section' => 'duo_colors',
        'settings' => 'duo_secondary_color',
    )));

    // アクセントカラー（ゴールド）
    $wp_customize->add_setting('duo_accent_color', array(
        'default' => '#DAA520',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'duo_accent_color', array(
        'label' => 'アクセントカラー（ゴールド）',
        'section' => 'duo_colors',
        'settings' => 'duo_accent_color',
    )));

    // ライトバックグラウンド
    $wp_customize->add_setting('duo_light_bg_color', array(
        'default' => '#F8F9FA',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'duo_light_bg_color', array(
        'label' => 'ライトバックグラウンド',
        'section' => 'duo_colors',
        'settings' => 'duo_light_bg_color',
    )));
}
add_action('customize_register', 'duo_communications_customize_register');

/**
 * Astraのデフォルト設定をオーバーライド
 */
function duo_communications_astra_settings() {
    // ヘッダー設定
    set_theme_mod('header-main-layout-width', 'full');
    set_theme_mod('header-main-menu-label', '');
    set_theme_mod('header-main-submenu-border', false);
    
    // フォント設定
    set_theme_mod('headings-font-family', 'Noto Sans JP, sans-serif');
    set_theme_mod('body-font-family', 'Noto Sans JP, sans-serif');
    
    // レイアウト設定
    set_theme_mod('site-layout', 'ast-full-width-layout');
    set_theme_mod('site-content-layout', 'content-boxed-container');
    
    // フッター設定
    $primary_color = get_theme_mod('duo_primary_color', '#000000');
    set_theme_mod('footer-color', $primary_color);
    set_theme_mod('footer-bg-obj-responsive', array(
        'desktop' => array(
            'background-color' => $primary_color,
        ),
        'tablet' => array(
            'background-color' => $primary_color,
        ),
        'mobile' => array(
            'background-color' => $primary_color,
        ),
    ));
}
add_action('after_setup_theme', 'duo_communications_astra_settings');

/**
 * Google Fonts読み込み
 */
function duo_communications_enqueue_fonts() {
    wp_enqueue_style(
        'google-fonts-noto-sans-jp', 
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap', 
        false,
        DUO_THEME_VERSION
    );
}
add_action('wp_enqueue_scripts', 'duo_communications_enqueue_fonts');

/**
 * カスタムCSS出力
 */
function duo_communications_custom_styles() {
    $primary_color = get_theme_mod('duo_primary_color', '#000000');
    $secondary_color = get_theme_mod('duo_secondary_color', '#333333');
    $accent_color = get_theme_mod('duo_accent_color', '#DAA520');
    $light_bg_color = get_theme_mod('duo_light_bg_color', '#F8F9FA');
    
    $css = "
    :root {
        --duo-primary-color: {$primary_color};
        --duo-secondary-color: {$secondary_color};
        --duo-accent-color: {$accent_color};
        --duo-light-bg: {$light_bg_color};
    }
    
    /* 基本フォント設定 */
    body, input, textarea, select, button {
        font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    /* ヘッダー設定 */
    .main-header-menu {
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
    }
    
    .main-header-menu a {
        color: white;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .main-header-menu a:hover {
        color: var(--duo-accent-color);
    }
    
    /* 透明ヘッダー設定 */
    .ast-theme-transparent-header .main-header-menu a,
    .ast-theme-transparent-header .ast-masthead-custom-menu-items a {
        color: white;
    }
    
    /* アクセントカラーの適用 */
    a:hover,
    .entry-title a:hover,
    .ast-button,
    input[type='submit'],
    button[type='submit'] {
        color: var(--duo-accent-color);
    }
    
    .ast-button,
    input[type='submit'],
    button[type='submit'] {
        background-color: var(--duo-accent-color);
        border-color: var(--duo-accent-color);
        transition: all 0.3s ease;
    }
    
    .ast-button:hover,
    input[type='submit']:hover,
    button[type='submit']:hover {
        background-color: var(--duo-primary-color);
        border-color: var(--duo-primary-color);
        transform: translateY(-2px);
    }
    
    /* フッター */
    .site-footer {
        background-color: var(--duo-primary-color);
        color: white;
    }
    
    .site-footer a {
        color: white;
        transition: color 0.3s ease;
    }
    
    .site-footer a:hover {
        color: var(--duo-accent-color);
    }
    
    /* カスタム投稿タイプ用スタイル */
    .business-divisions-archive .business-card,
    .job-postings-archive .job-item {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .business-divisions-archive .business-card:hover,
    .job-postings-archive .job-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    /* レスポンシブ対応 */
    @media (max-width: 768px) {
        .main-header-menu {
            padding: 1rem;
        }
        
        .business-divisions-archive .business-card,
        .job-postings-archive .job-item {
            padding: 1.5rem;
        }
    }
    ";
    
    // Astraテーマの既存CSSに追加
    wp_add_inline_style('astra-theme-css', $css);
}
add_action('wp_enqueue_scripts', 'duo_communications_custom_styles');

/**
 * ページタイトルのカスタマイズ
 */
function duo_communications_custom_title($title_parts) {
    if (is_front_page()) {
        $title_parts['title'] = '株式会社デュオコミュニケーションズ';
        $title_parts['tagline'] = 'つながりから生まれる新たな価値で、すべての人とビジネスの可能性を拓く';
    } elseif (is_page()) {
        $page_title_map = array(
            'about' => 'Information-企業情報-',
            'contact' => 'Contact-お問い合わせ-',
        );
        
        $page_slug = get_post_field('post_name', get_queried_object_id());
        if (isset($page_title_map[$page_slug])) {
            $title_parts['title'] = $page_title_map[$page_slug];
        }
    } elseif (is_post_type_archive('business_divisions')) {
        $title_parts['title'] = 'Service-事業情報-';
    } elseif (is_post_type_archive('job_postings')) {
        $title_parts['title'] = 'Recruitment-採用情報-';
    }
    
    return $title_parts;
}
add_filter('document_title_parts', 'duo_communications_custom_title');

/**
 * OGP設定
 */
function duo_communications_add_ogp() {
    if (is_front_page()) {
        echo '<meta property="og:title" content="株式会社デュオコミュニケーションズ">' . "\n";
        echo '<meta property="og:description" content="つながりから生まれる新たな価値で、すべての人とビジネスの可能性を拓く">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
        echo '<meta property="og:site_name" content="株式会社デュオコミュニケーションズ">' . "\n";
    } elseif (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 20)) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'large');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_url) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'duo_communications_add_ogp');

/**
 * JSON-LD構造化データ
 */
function duo_communications_add_structured_data() {
    if (is_front_page()) {
        $structured_data = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => '株式会社デュオコミュニケーションズ',
            'url' => home_url(),
            'description' => 'つながりから生まれる新たな価値で、すべての人とビジネスの可能性を拓く',
            'foundingDate' => '2023-06-02',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressCountry' => 'JP',
                'addressRegion' => '東京都',
                'addressLocality' => '港区',
                'streetAddress' => '南青山3-1-36 青山丸竹ビル 6F'
            ),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'availableLanguage' => 'Japanese'
            )
        );
        
        echo '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'duo_communications_add_structured_data');
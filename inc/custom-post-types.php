<?php
/**
 * カスタム投稿タイプの設定
 * 株式会社デュオコミュニケーションズ
 */

// 直接アクセスを防ぐ
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 事業部投稿タイプ（business_divisions）
 */
function create_business_divisions_post_type() {
    $labels = array(
        'name' => '事業部管理',
        'singular_name' => '事業部',
        'add_new' => '新規追加',
        'add_new_item' => '新しい事業部を追加',
        'edit_item' => '事業部を編集',
        'new_item' => '新しい事業部',
        'view_item' => '事業部を表示',
        'search_items' => '事業部を検索',
        'not_found' => '事業部が見つかりませんでした',
        'not_found_in_trash' => 'ゴミ箱に事業部はありませんでした',
        'menu_name' => '事業部管理'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'business'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-building',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true
    );

    register_post_type('business_divisions', $args);
}
add_action('init', 'create_business_divisions_post_type');

/**
 * 求人投稿タイプ（job_postings）
 */
function create_job_postings_post_type() {
    $labels = array(
        'name' => '求人管理',
        'singular_name' => '求人',
        'add_new' => '新規追加',
        'add_new_item' => '新しい求人を追加',
        'edit_item' => '求人を編集',
        'new_item' => '新しい求人',
        'view_item' => '求人を表示',
        'search_items' => '求人を検索',
        'not_found' => '求人が見つかりませんでした',
        'not_found_in_trash' => 'ゴミ箱に求人はありませんでした',
        'menu_name' => '求人管理'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'jobs'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-businessman',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true
    );

    register_post_type('job_postings', $args);
}
add_action('init', 'create_job_postings_post_type');

/**
 * 事業部投稿のメタボックス追加
 */
function add_business_divisions_meta_boxes() {
    add_meta_box(
        'business_divisions_details',
        '事業部詳細情報',
        'business_divisions_meta_box_callback',
        'business_divisions',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_business_divisions_meta_boxes');

/**
 * 事業部メタボックスのコールバック
 */
function business_divisions_meta_box_callback($post) {
    wp_nonce_field('business_divisions_meta_box', 'business_divisions_meta_box_nonce');
    
    $abbreviation = get_post_meta($post->ID, '_abbreviation', true);
    $overview = get_post_meta($post->ID, '_overview', true);
    $display_order = get_post_meta($post->ID, '_display_order', true);
    $link_url = get_post_meta($post->ID, '_link_url', true);
    $hover_image = get_post_meta($post->ID, '_hover_image', true);
    
    echo '<table class="form-table">';
    
    echo '<tr><th><label for="abbreviation">略称</label></th>';
    echo '<td><input type="text" id="abbreviation" name="abbreviation" value="' . esc_attr($abbreviation) . '" size="10" placeholder="例: BI, LD, AL" /></td></tr>';
    
    echo '<tr><th><label for="overview">事業概要</label></th>';
    echo '<td><textarea id="overview" name="overview" rows="3" cols="50" placeholder="事業部の概要を入力してください">' . esc_textarea($overview) . '</textarea></td></tr>';
    
    echo '<tr><th><label for="display_order">表示順序</label></th>';
    echo '<td><input type="number" id="display_order" name="display_order" value="' . esc_attr($display_order) . '" min="1" /></td></tr>';
    
    echo '<tr><th><label for="link_url">リンク先URL</label></th>';
    echo '<td><input type="url" id="link_url" name="link_url" value="' . esc_attr($link_url) . '" size="50" placeholder="https://example.com" /></td></tr>';
    
    echo '<tr><th><label for="hover_image">ホバー背景画像URL</label></th>';
    echo '<td><input type="url" id="hover_image" name="hover_image" value="' . esc_attr($hover_image) . '" size="50" placeholder="画像のURL" /></td></tr>';
    
    echo '</table>';
}

/**
 * 事業部メタボックスの保存
 */
function save_business_divisions_meta_box($post_id) {
    if (!isset($_POST['business_divisions_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['business_divisions_meta_box_nonce'], 'business_divisions_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['post_type']) && 'business_divisions' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    $fields = array('abbreviation', 'overview', 'display_order', 'link_url', 'hover_image');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_business_divisions_meta_box');

/**
 * 求人投稿のメタボックス追加
 */
function add_job_postings_meta_boxes() {
    add_meta_box(
        'job_postings_details',
        '求人詳細情報',
        'job_postings_meta_box_callback',
        'job_postings',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_job_postings_meta_boxes');

/**
 * 求人メタボックスのコールバック
 */
function job_postings_meta_box_callback($post) {
    wp_nonce_field('job_postings_meta_box', 'job_postings_meta_box_nonce');
    
    $job_category = get_post_meta($post->ID, '_job_category', true);
    $employment_type = get_post_meta($post->ID, '_employment_type', true);
    $salary = get_post_meta($post->ID, '_salary', true);
    $location = get_post_meta($post->ID, '_location', true);
    $working_hours = get_post_meta($post->ID, '_working_hours', true);
    $holidays = get_post_meta($post->ID, '_holidays', true);
    $qualifications = get_post_meta($post->ID, '_qualifications', true);
    $benefits = get_post_meta($post->ID, '_benefits', true);
    $recruitment_status = get_post_meta($post->ID, '_recruitment_status', true);
    
    echo '<table class="form-table">';
    
    echo '<tr><th><label for="job_category">職種カテゴリ</label></th>';
    echo '<td><select id="job_category" name="job_category">';
    echo '<option value="">選択してください</option>';
    echo '<option value="営業職"' . selected($job_category, '営業職', false) . '>営業職</option>';
    echo '<option value="管理部門"' . selected($job_category, '管理部門', false) . '>管理部門</option>';
    echo '</select></td></tr>';
    
    echo '<tr><th><label for="employment_type">雇用形態</label></th>';
    echo '<td><select id="employment_type" name="employment_type">';
    echo '<option value="">選択してください</option>';
    echo '<option value="正社員"' . selected($employment_type, '正社員', false) . '>正社員</option>';
    echo '<option value="契約社員"' . selected($employment_type, '契約社員', false) . '>契約社員</option>';
    echo '<option value="アルバイト"' . selected($employment_type, 'アルバイト', false) . '>アルバイト</option>';
    echo '</select></td></tr>';
    
    echo '<tr><th><label for="salary">給与</label></th>';
    echo '<td><input type="text" id="salary" name="salary" value="' . esc_attr($salary) . '" size="50" placeholder="例: 月給25万円〜40万円" /></td></tr>';
    
    echo '<tr><th><label for="location">勤務地</label></th>';
    echo '<td><input type="text" id="location" name="location" value="' . esc_attr($location) . '" size="50" /></td></tr>';
    
    echo '<tr><th><label for="working_hours">勤務時間</label></th>';
    echo '<td><input type="text" id="working_hours" name="working_hours" value="' . esc_attr($working_hours) . '" size="50" placeholder="例: 10:00〜19:00" /></td></tr>';
    
    echo '<tr><th><label for="holidays">休日・休暇</label></th>';
    echo '<td><textarea id="holidays" name="holidays" rows="3" cols="50">' . esc_textarea($holidays) . '</textarea></td></tr>';
    
    echo '<tr><th><label for="qualifications">応募資格</label></th>';
    echo '<td><textarea id="qualifications" name="qualifications" rows="4" cols="50">' . esc_textarea($qualifications) . '</textarea></td></tr>';
    
    echo '<tr><th><label for="benefits">待遇・福利厚生</label></th>';
    echo '<td><textarea id="benefits" name="benefits" rows="4" cols="50">' . esc_textarea($benefits) . '</textarea></td></tr>';
    
    echo '<tr><th><label for="recruitment_status">募集状況</label></th>';
    echo '<td><select id="recruitment_status" name="recruitment_status">';
    echo '<option value="募集中"' . selected($recruitment_status, '募集中', false) . '>募集中</option>';
    echo '<option value="募集停止"' . selected($recruitment_status, '募集停止', false) . '>募集停止</option>';
    echo '</select></td></tr>';
    
    echo '</table>';
}

/**
 * 求人メタボックスの保存
 */
function save_job_postings_meta_box($post_id) {
    if (!isset($_POST['job_postings_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['job_postings_meta_box_nonce'], 'job_postings_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['post_type']) && 'job_postings' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    $fields = array('job_category', 'employment_type', 'salary', 'location', 'working_hours', 'holidays', 'qualifications', 'benefits', 'recruitment_status');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_job_postings_meta_box');

/**
 * フロントエンドでの表示順序を設定
 */
function business_divisions_pre_get_posts($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home() && $query->is_main_query()) {
            return;
        }
        if ($query->get('post_type') == 'business_divisions') {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_display_order');
            $query->set('order', 'ASC');
        }
    }
}
add_action('pre_get_posts', 'business_divisions_pre_get_posts');

/**
 * リライトルールをフラッシュ
 */
function duo_custom_post_types_flush_rewrite_rules() {
    create_business_divisions_post_type();
    create_job_postings_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'duo_custom_post_types_flush_rewrite_rules');
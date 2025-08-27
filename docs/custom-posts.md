# カスタム投稿タイプ仕様書
## 株式会社デュオコミュニケーションズ コーポレートサイト

### 1. 概要
WordPressのカスタム投稿タイプを使用して、事業部情報と求人情報を効率的に管理します。

### 2. カスタム投稿タイプ一覧

#### 2.1 事業部情報（business_divisions）
**目的**: 各事業部の情報を個別に管理・表示

#### 2.2 求人情報（job_postings）
**目的**: 採用情報を職種別に管理・公開

### 3. 事業部情報（business_divisions）詳細仕様

#### 3.1 基本設定
```php
// 投稿タイプ設定
'labels' => array(
    'name'               => '事業部',
    'singular_name'      => '事業部',
    'menu_name'          => '事業部管理',
    'add_new'            => '新規追加',
    'add_new_item'       => '新規事業部を追加',
    'edit_item'          => '事業部を編集',
    'new_item'           => '新規事業部',
    'view_item'          => '事業部を表示',
    'search_items'       => '事業部を検索',
    'not_found'          => '事業部が見つかりませんでした',
    'not_found_in_trash' => 'ゴミ箱に事業部はありません',
),
'public'             => true,
'publicly_queryable' => true,
'show_ui'            => true,
'show_in_menu'       => true,
'query_var'          => true,
'rewrite'            => array('slug' => 'business'),
'capability_type'    => 'post',
'has_archive'        => true,
'hierarchical'       => false,
'menu_position'      => 5,
'menu_icon'          => 'dashicons-groups',
'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
'show_in_rest'       => true, // Gutenberg対応
```

#### 3.2 カスタムフィールド
| フィールド名 | フィールドID | タイプ | 必須 | 説明 |
|------------|-------------|-------|------|------|
| 事業部名 | division_name | テキスト | ○ | 正式な事業部名 |
| 略称 | division_short_name | テキスト | ○ | 表示用の短い名前 |
| 設立年月 | established_date | 日付 | ○ | 事業部設立日 |
| 責任者名 | manager_name | テキスト | ○ | 事業部責任者 |
| 従業員数 | employee_count | 数値 | ○ | 事業部の人数 |
| 主要サービス | main_services | テキストエリア | ○ | 提供サービス一覧 |
| 実績数値 | achievements | 繰り返しフィールド | × | 実績データ |
| ターゲット業界 | target_industries | チェックボックス | ○ | 対象業界 |
| 営業エリア | sales_areas | 選択 | ○ | 活動地域 |
| 特徴・強み | strengths | WYSIWYG | ○ | 事業部の特徴 |
| 事例紹介 | case_studies | 繰り返しフィールド | × | 成功事例 |
| チーム写真 | team_photo | 画像 | × | チーム集合写真 |
| 紹介動画URL | intro_video_url | URL | × | YouTube等の動画 |
| お問い合わせ先 | contact_info | グループ | ○ | 連絡先情報 |
| 表示順序 | display_order | 数値 | ○ | 一覧での表示順 |

#### 3.3 カスタムタクソノミー
**事業カテゴリ（business_category）**
- toB営業
- toC営業
- インサイドセールス
- フィールドセールス
- カスタマーサクセス

**取扱商材（product_type）**
- IT・ソフトウェア
- 通信・インフラ
- 金融商品
- 不動産
- 人材サービス
- その他

#### 3.4 テンプレート構造
```
archive-business_divisions.php  # 一覧ページ
single-business_divisions.php   # 詳細ページ
content-business_divisions.php  # コンテンツ部分
```

#### 3.5 URL構造
- 一覧: `/business/`
- 詳細: `/business/[slug]/`
- カテゴリ: `/business-category/[category-slug]/`

### 4. 求人情報（job_postings）詳細仕様

#### 4.1 基本設定
```php
'labels' => array(
    'name'               => '求人情報',
    'singular_name'      => '求人',
    'menu_name'          => '求人管理',
    'add_new'            => '新規求人追加',
    'add_new_item'       => '新規求人を追加',
    'edit_item'          => '求人を編集',
    'new_item'           => '新規求人',
    'view_item'          => '求人を表示',
    'search_items'       => '求人を検索',
    'not_found'          => '求人が見つかりませんでした',
    'not_found_in_trash' => 'ゴミ箱に求人はありません',
),
'public'             => true,
'publicly_queryable' => true,
'show_ui'            => true,
'show_in_menu'       => true,
'query_var'          => true,
'rewrite'            => array('slug' => 'careers'),
'capability_type'    => 'post',
'has_archive'        => true,
'hierarchical'       => false,
'menu_position'      => 6,
'menu_icon'          => 'dashicons-id-alt',
'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
'show_in_rest'       => true,
```

#### 4.2 カスタムフィールド
| フィールド名 | フィールドID | タイプ | 必須 | 説明 |
|------------|-------------|-------|------|------|
| 職種名 | job_title | テキスト | ○ | 募集職種 |
| 雇用形態 | employment_type | ラジオボタン | ○ | 正社員/契約/派遣等 |
| 勤務地 | work_location | テキスト | ○ | 勤務場所 |
| 給与範囲 | salary_range | テキスト | ○ | 年収・月給 |
| 必須スキル | required_skills | テキストエリア | ○ | 必須要件 |
| 歓迎スキル | preferred_skills | テキストエリア | × | 歓迎要件 |
| 仕事内容 | job_description | WYSIWYG | ○ | 詳細な業務内容 |
| 応募資格 | qualifications | WYSIWYG | ○ | 応募条件 |
| 福利厚生 | benefits | WYSIWYG | ○ | 待遇・福利厚生 |
| 選考プロセス | selection_process | テキストエリア | ○ | 選考フロー |
| 募集人数 | positions_available | 数値 | ○ | 採用予定数 |
| 募集期限 | application_deadline | 日付 | × | 応募締切日 |
| 所属事業部 | division_id | 選択（関連） | ○ | 配属先事業部 |
| 募集状況 | recruitment_status | 選択 | ○ | 募集中/終了/一時停止 |
| 応募フォームURL | application_form_url | URL | × | 外部応募フォーム |
| 担当者名 | recruiter_name | テキスト | ○ | 採用担当者 |
| 問い合わせ先 | contact_email | メール | ○ | 連絡先メール |
| 表示優先度 | priority | 数値 | ○ | 表示順序 |

#### 4.3 カスタムタクソノミー
**職種カテゴリ（job_category）**
- 営業職
- 企画・マーケティング
- 事務・管理
- エンジニア・技術職
- マネジメント

**経験レベル（experience_level）**
- 新卒
- 第二新卒
- 未経験歓迎
- 経験者優遇
- マネージャー候補

**勤務地エリア（work_area）**
- 東京23区
- 東京都下
- 神奈川県
- 千葉県
- 埼玉県
- リモート可

#### 4.4 テンプレート構造
```
archive-job_postings.php  # 求人一覧ページ
single-job_postings.php   # 求人詳細ページ
content-job_postings.php  # コンテンツ部分
taxonomy-job_category.php # カテゴリ別一覧
```

#### 4.5 URL構造
- 一覧: `/careers/`
- 詳細: `/careers/[job-slug]/`
- カテゴリ: `/job-category/[category-slug]/`

### 5. 管理画面カスタマイズ

#### 5.1 一覧画面のカラム表示

**事業部一覧**
- 事業部名
- 責任者
- 従業員数
- 設立年月
- 表示順序
- ステータス

**求人一覧**
- 職種名
- 雇用形態
- 勤務地
- 募集状況
- 応募期限
- 優先度

#### 5.2 クイック編集対応フィールド
- 表示順序
- ステータス
- 優先度

#### 5.3 一括操作
- 公開/非公開切り替え
- カテゴリ一括変更
- ステータス一括更新

### 6. REST API対応

#### 6.1 エンドポイント
```
GET /wp-json/wp/v2/business_divisions
GET /wp-json/wp/v2/business_divisions/{id}
GET /wp-json/wp/v2/job_postings
GET /wp-json/wp/v2/job_postings/{id}
```

#### 6.2 カスタムフィールドの露出
```php
// REST APIでカスタムフィールドを含める
register_rest_field('business_divisions', 'division_meta', array(
    'get_callback' => 'get_division_meta_for_api',
    'schema' => null,
));
```

### 7. 検索・フィルタリング機能

#### 7.1 事業部検索
- フリーワード検索
- カテゴリフィルター
- 営業エリアフィルター
- 並び替え（名前順、設立順、人数順）

#### 7.2 求人検索
- 職種キーワード検索
- 雇用形態フィルター
- 勤務地フィルター
- 経験レベルフィルター
- 給与範囲フィルター
- 並び替え（新着順、締切順、優先度順）

### 8. 関連機能

#### 8.1 関連表示
- 事業部詳細ページに関連求人を表示
- 求人詳細ページに所属事業部情報を表示

#### 8.2 お気に入り機能（将来実装）
- Cookieベースのお気に入り保存
- お気に入り求人一覧ページ

#### 8.3 応募管理（将来実装）
- 応募フォーム統合
- 応募者情報のDB保存
- 応募通知メール

### 9. パフォーマンス最適化

#### 9.1 キャッシュ戦略
- オブジェクトキャッシュ使用
- トランジェントAPIでのクエリ結果保存
- 静的HTMLキャッシュ

#### 9.2 クエリ最適化
```php
// 必要なフィールドのみ取得
$args = array(
    'post_type' => 'business_divisions',
    'posts_per_page' => 10,
    'fields' => 'ids', // IDのみ取得
    'meta_key' => 'display_order',
    'orderby' => 'meta_value_num',
    'order' => 'ASC'
);
```

### 10. セキュリティ考慮事項

#### 10.1 権限管理
- 編集権限は編集者以上
- 公開権限は管理者のみ
- カスタムケイパビリティの設定

#### 10.2 入力値検証
- サニタイゼーション実装
- エスケープ処理
- nonce検証

#### 10.3 データ保護
- 個人情報の暗号化
- SQLインジェクション対策
- XSS対策

### 11. 多言語対応（将来実装）

#### 11.1 対応言語
- 日本語（デフォルト）
- 英語

#### 11.2 翻訳対象
- 投稿タイトル
- 本文
- カスタムフィールド
- タクソノミー

### 12. インポート/エクスポート

#### 12.1 CSVインポート
- 事業部情報の一括登録
- 求人情報の一括登録

#### 12.2 エクスポート機能
- 投稿データのCSV出力
- バックアップ用XMLエクスポート

---
*この仕様書はバージョン1.0です。実装時に詳細を調整する可能性があります。*
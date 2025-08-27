# デザイン仕様書
## 株式会社デュオコミュニケーションズ コーポレートサイト

### 1. デザインコンセプト

#### 1.1 ビジュアルコンセプト
- **キーワード**: プロフェッショナル、信頼感、革新性、成長
- **トーン&マナー**: モダン、クリーン、ミニマル
- **印象**: 洗練された営業会社としての専門性と親しみやすさの両立

#### 1.2 デザイン原則
1. **明確性**: 情報が直感的に理解できる
2. **一貫性**: 全ページで統一されたデザイン言語
3. **アクセシビリティ**: すべてのユーザーが利用しやすい
4. **レスポンシブ**: あらゆるデバイスで最適な表示

### 2. カラースキーム

#### 2.1 ブランドカラー
```css
:root {
  /* Primary Colors */
  --duo-black: #000000;        /* メインカラー */
  --duo-dark-gray: #333333;    /* サブカラー */
  --duo-gold: #DAA520;         /* アクセントカラー */
  
  /* Secondary Colors */
  --duo-light-gray: #F8F9FA;   /* 背景色 */
  --duo-white: #FFFFFF;        /* ベース色 */
  --duo-medium-gray: #6C757D;  /* テキスト補助色 */
  
  /* Functional Colors */
  --duo-success: #28A745;      /* 成功 */
  --duo-warning: #FFC107;      /* 警告 */
  --duo-error: #DC3545;        /* エラー */
  --duo-info: #17A2B8;         /* 情報 */
}
```

#### 2.2 カラー使用ガイドライン
- **ヘッダー/フッター**: 黒（#000000）背景に白テキスト
- **メインコンテンツ**: 白背景に黒/ダークグレーテキスト
- **CTA（Call to Action）**: ゴールド（#DAA520）
- **セクション区切り**: ライトグレー（#F8F9FA）

### 3. タイポグラフィ

#### 3.1 フォントファミリー
```css
/* 日本語フォント */
font-family: 'Noto Sans JP', sans-serif;

/* 英語フォント */
font-family: 'Inter', sans-serif;

/* フォールバック */
font-family: 'Noto Sans JP', 'Inter', -apple-system, BlinkMacSystemFont, 
             'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
```

#### 3.2 フォントサイズ階層
```css
/* Desktop */
--font-size-h1: 48px;    /* ページタイトル */
--font-size-h2: 36px;    /* セクションタイトル */
--font-size-h3: 28px;    /* サブセクション */
--font-size-h4: 24px;    /* カード見出し */
--font-size-h5: 20px;    /* 小見出し */
--font-size-body: 16px;  /* 本文 */
--font-size-small: 14px; /* 注釈 */

/* Mobile */
--font-size-h1-mobile: 32px;
--font-size-h2-mobile: 28px;
--font-size-h3-mobile: 24px;
--font-size-h4-mobile: 20px;
--font-size-h5-mobile: 18px;
--font-size-body-mobile: 16px;
--font-size-small-mobile: 14px;
```

#### 3.3 フォントウェイト
- Light: 300（補助テキスト）
- Regular: 400（本文）
- Medium: 500（強調）
- Semi-Bold: 600（見出し）
- Bold: 700（重要な見出し）

#### 3.4 行間・文字間
```css
/* 行間（line-height） */
--line-height-heading: 1.3;
--line-height-body: 1.7;
--line-height-tight: 1.2;

/* 文字間（letter-spacing） */
--letter-spacing-heading: 0.02em;
--letter-spacing-body: 0.03em;
--letter-spacing-wide: 0.1em;
```

### 4. レイアウトシステム

#### 4.1 グリッドシステム
- **カラム数**: 12カラム
- **ガター幅**: 30px（デスクトップ）、20px（モバイル）
- **最大幅**: 1200px
- **パディング**: 左右各20px（モバイル）、40px（デスクトップ）

#### 4.2 ブレークポイント
```css
/* Breakpoints */
--breakpoint-xs: 0;       /* Extra small devices */
--breakpoint-sm: 576px;   /* Small devices */
--breakpoint-md: 768px;   /* Medium devices (Tablets) */
--breakpoint-lg: 1024px;  /* Large devices (Desktop) */
--breakpoint-xl: 1200px;  /* Extra large devices */
--breakpoint-xxl: 1400px; /* Extra extra large devices */
```

#### 4.3 スペーシングシステム
```css
/* Spacing Scale (8px base) */
--spacing-xs: 4px;   /* 0.5x */
--spacing-sm: 8px;   /* 1x */
--spacing-md: 16px;  /* 2x */
--spacing-lg: 24px;  /* 3x */
--spacing-xl: 32px;  /* 4x */
--spacing-2xl: 48px; /* 6x */
--spacing-3xl: 64px; /* 8x */
--spacing-4xl: 96px; /* 12x */
```

### 5. コンポーネント仕様

#### 5.1 ボタン
```css
/* Primary Button */
.btn-primary {
  background: var(--duo-gold);
  color: var(--duo-black);
  padding: 12px 32px;
  border-radius: 4px;
  font-weight: 600;
  transition: all 0.3s ease;
}

/* Secondary Button */
.btn-secondary {
  background: transparent;
  color: var(--duo-black);
  border: 2px solid var(--duo-black);
  padding: 10px 30px;
  border-radius: 4px;
}

/* Button Sizes */
.btn-small { padding: 8px 24px; font-size: 14px; }
.btn-large { padding: 16px 40px; font-size: 18px; }
```

#### 5.2 カード
```css
.card {
  background: white;
  border-radius: 8px;
  padding: 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
```

#### 5.3 フォーム要素
```css
/* Input Fields */
.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #DDD;
  border-radius: 4px;
  font-size: 16px;
  transition: border-color 0.3s ease;
}

.form-input:focus {
  border-color: var(--duo-gold);
  outline: none;
}

/* Labels */
.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: var(--duo-dark-gray);
}
```

### 6. アニメーション仕様

#### 6.1 ファーストビューアニメーション
- **MP4動画再生**: リピートなし・自動再生
- **配置**: ファーストビュー全画面背景
- **コントロール**: 非表示
- **パフォーマンス**: 遅延読み込み対応

#### 6.2 スクロールアニメーション
- **MVVセクション**: スクロールで現れたときに「じわっと浮き出てくる」フェードイン
- **実装**: Intersection Observer API使用
- **タイミング**: 要素が50%画面内に入ったとき
- **イージング**: ease-out

#### 6.3 事業部セクションエフェクト
```css
/* ホバー時の動作 */
.business-card:hover {
  transform: scale(1.1);
  z-index: 10;
}

.business-section:hover .business-card:not(:hover) {
  transform: scale(0.9);
  opacity: 0.6;
}

/* 背景画像変更 */
.business-section[data-bg="BI"] {
  background-image: url('assets/images/bg-bi.jpg');
}
```

#### 6.4 基本トランジション
```css
/* Standard Transitions */
--transition-fast: 0.2s ease;
--transition-normal: 0.3s ease;
--transition-slow: 0.5s ease;

/* Business Card Transitions */
--transition-hover: 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
--transition-bg: 0.6s ease-in-out;
```

#### 6.5 お知らせセクション
- **もっと見るボタン**: クリック時に5件ずつ追加表示
- **アニメーション**: フェードインで表示
- **Ajax読み込み**: 非同期でコンテンツ取得

### 7. アイコン仕様

#### 7.1 アイコンセット
- **使用ライブラリ**: Font Awesome 6 Pro
- **スタイル**: Solid, Regular, Light
- **サイズ**: 16px, 24px, 32px, 48px

#### 7.2 カスタムアイコン
- 事業部アイコン（SVG形式）
- ソーシャルメディアアイコン
- 矢印・装飾アイコン

### 8. 画像仕様

#### 8.1 画像サイズガイドライン
- **ヒーローイメージ**: 1920x1080px
- **セクション背景**: 1920x800px
- **カード画像**: 600x400px
- **サムネイル**: 300x200px
- **アイコン**: 64x64px, 128x128px

#### 8.2 画像最適化
- **形式**: WebP（フォールバックでJPEG/PNG）
- **品質**: 85%（JPEG）、可逆圧縮（PNG）
- **遅延読み込み**: Lazy Loading実装
- **レスポンシブ画像**: srcset使用

### 9. ページ別デザイン仕様

#### 9.1 トップページ（ホーム）
1. **セクション1: ファーストビュー・メニューバー**
   - 左上: 企業ロゴ
   - 画面下部: メニューバー
   - 背景: MP4動画（リピートなし）

2. **セクション2: ミッション・ビジョン・バリュー**
   - スクロールアニメーション（じわっと浮き出る）
   - 3つのセクション縦配置

3. **セクション3: 事業部紹介**
   - 6カード（サービス + 5事業部）
   - ホバー時: 拡大・縮小・透明度変更
   - 背景画像: 各項目ごとに設定

4. **セクション4: お知らせ欄**
   - 最新4件表示
   - もっと見るボタン（5件ずつ追加）

5. **セクション5: フッター**
   - メニューと同じ内容
   - Instagram連携

#### 9.2 企業情報ページ
- **ページタイトル**: Information-企業情報-
- **パンくずリスト**: ホーム > 企業情報
- **MVVセクション**: トップページ同様のアニメーション・色変更
- **会社概要**: シンプルなテーブル形式

#### 9.3 事業情報ページ
- **ページタイトル**: Service-事業情報-
- **5事業部カード**: BI, AL, SB, LD, DM
- **ホバーエフェクト**: トップページと同じ
- **詳細ページ遷移**: 各カードクリックで移動

#### 9.4 事業詳細ページ
- **ページタイトル**: 事業名
- **パンくずリスト**: ホーム > 事業情報 > 事業名
- **事業表示画像**: 大きな画像エリア
- **事業部紹介文**: 詳細テキストエリア

#### 9.5 採用情報ページ
- **3セクション構成**:
  1. 代表メッセージ
  2. 求人情報欄（写真付きカード）
  3. 教育制度（3ポイント + 画像）

#### 9.6 求人詳細ページ
- **本文欄**: 自由レイアウト対応
- **求人情報詳細欄**: 表形式（給与・勤務体系等）

#### 9.7 ブログページ
- **2列×3行グリッド**（6記事表示）
- **ページネーション**: 1,2,3,4,5形式
- **タグ検索機能**

#### 9.8 ブログ詳細ページ
- **2カラムレイアウト**: メイン + サイドバー
- **関連記事**: 同じタグの記事を5件表示

#### 9.9 お問い合わせページ
- **シンプルフォーム**: tayori埋め込み対応
- **項目**: 会社名・お名前・ふりがな・メール・内容

### 10. レスポンシブデザイン

#### 10.1 モバイルファースト
- 基本設計は320px幅から
- プログレッシブエンハンスメント
- タッチフレンドリーUI（最小44x44px）

#### 10.2 ブレークポイント別対応
**モバイル（〜767px）**
- シングルカラム
- ハンバーガーメニュー
- 縮小版ロゴ

**タブレット（768px〜1023px）**
- 2カラムグリッド
- サイドバー表示
- 中サイズロゴ

**デスクトップ（1024px〜）**
- マルチカラム
- フルナビゲーション
- フルサイズロゴ

### 11. アクセシビリティデザイン

#### 11.1 カラーコントラスト
- 通常テキスト: 最小4.5:1
- 大きいテキスト: 最小3:1
- インタラクティブ要素: 最小3:1

#### 11.2 フォーカスインジケーター
```css
:focus {
  outline: 2px solid var(--duo-gold);
  outline-offset: 2px;
}
```

#### 11.3 読みやすさ
- 最小フォントサイズ: 14px
- 行の最大幅: 70文字
- 適切な行間と余白

### 12. ダークモード対応（将来実装）

#### 12.1 カラーパレット
```css
[data-theme="dark"] {
  --bg-primary: #1A1A1A;
  --bg-secondary: #2D2D2D;
  --text-primary: #FFFFFF;
  --text-secondary: #B0B0B0;
  --accent: #FFD700;
}
```

### 13. パフォーマンス考慮

#### 13.1 CSS最適化
- Critical CSS インライン化
- 未使用CSSの削除
- CSS圧縮・結合

#### 13.2 アニメーション最適化
- GPU加速プロパティ使用
- will-changeの適切な使用
- アニメーション数の制限

### 14. デザインシステム管理

#### 14.1 命名規則
- BEM方式採用
- プレフィックス: `duo-`
- 状態クラス: `is-active`, `has-error`

#### 14.2 ファイル構成
```
assets/css/
├── base/          # リセット、変数
├── components/    # コンポーネント
├── layouts/       # レイアウト
├── pages/         # ページ固有
├── utils/         # ユーティリティ
└── main.css       # エントリーポイント
```

---
*このデザイン仕様書はバージョン1.0です。プロジェクトの進行に応じて更新されます。*
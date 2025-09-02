# 投稿 API v1 ドキュメント

## エンドポイント

### 1. 全投稿を取得

**GET** `/wp-json/api/v1/posts`

**レスポンス例:**

```json
{
	"posts": [
		{
			"id": 61,
			"title": "サンプル投稿タイトル",
			"content": "<p>投稿の完全なHTMLコンテンツがここに表示されます。</p>",
			"excerpt": "投稿の短い抜粋テキスト...",
			"slug": "sample-post-title",
			"date": {
				"published": "2025-07-03T10:30:00+00:00",
				"published_formatted": "July 3, 2025"
			},
			"featured_image": {
				"id": 25,
				"url": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image.jpg",
				"thumbnail": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image-150x150.jpg",
				"medium": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image-300x200.jpg",
				"large": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image-1024x683.jpg",
				"alt": "画像のalt属性"
			},
			"views": 15,
			"author": {
				"id": 1,
				"name": "管理者"
			}
		}
	]
}
```

### 2. ID で投稿を取得 (ビュー数増加)

**GET** `/wp-json/api/v1/posts/{id}`

**レスポンス例:**

```json
{
	"id": 61,
	"title": "サンプル投稿タイトル",
	"content": "<p>投稿の完全なHTMLコンテンツがここに表示されます。</p>",
	"excerpt": "投稿の短い抜粋テキスト...",
	"slug": "sample-post-title",
	"date": {
		"published": "2025-07-03T10:30:00+00:00",
		"published_formatted": "July 3, 2025"
	},
	"featured_image": {
		"id": 25,
		"url": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image.jpg",
		"thumbnail": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image-150x150.jpg",
		"medium": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image-300x200.jpg",
		"large": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/image-1024x683.jpg",
		"alt": "画像のalt属性"
	},
	"views": 16,
	"author": {
		"id": 1,
		"name": "管理者"
	}
}
```

### 3. スラッグで投稿を取得 (ビュー数増加)

**GET** `/wp-json/api/v1/posts/slug/{slug}`

**レスポンス例:** 上記「ID で投稿を取得」と同じ

### 4. 人気投稿を取得 (ビュー数上位 5 件)

**GET** `/wp-json/api/v1/posts/popular`

**レスポンス例:**

```json
{
	"posts": [
		{
			"id": 55,
			"title": "最も人気の投稿",
			"content": "<p>最も人気の投稿内容...</p>",
			"excerpt": "人気投稿の抜粋...",
			"slug": "most-popular-post",
			"date": {
				"published": "2025-07-01T15:20:00+00:00",
				"published_formatted": "July 1, 2025"
			},
			"featured_image": null,
			"views": 250,
			"author": {
				"id": 1,
				"name": "管理者"
			}
		},
		{
			"id": 42,
			"title": "2番目に人気の投稿",
			"content": "<p>2番目に人気の投稿内容...</p>",
			"excerpt": "2番目の抜粋...",
			"slug": "second-popular-post",
			"date": {
				"published": "2025-07-02T09:15:00+00:00",
				"published_formatted": "July 2, 2025"
			},
			"featured_image": {
				"id": 30,
				"url": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/popular.jpg",
				"thumbnail": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/popular-150x150.jpg",
				"medium": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/popular-300x200.jpg",
				"large": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/popular-1024x683.jpg",
				"alt": "人気投稿の画像"
			},
			"views": 189,
			"author": {
				"id": 1,
				"name": "管理者"
			}
		}
	]
}
```

### 5. ID で投稿のおすすめを取得

**GET** `/wp-json/api/v1/posts/{id}/recommend`

指定された投稿 ID に基づいて、関連する投稿を最大 5 件返します。

**動作ロジック:**

1. まず同じカテゴリの投稿を検索
2. 不足分はランダムに投稿を選択
3. 現在の投稿は除外

**レスポンス例:**

```json
{
	"recommended_posts": [
		{
			"id": 72,
			"title": "関連投稿タイトル1",
			"content": "<p>関連投稿の内容...</p>",
			"excerpt": "関連投稿の抜粋...",
			"slug": "related-post-1",
			"date": {
				"published": "2025-07-04T14:20:00+00:00",
				"published_formatted": "July 4, 2025"
			},
			"featured_image": {
				"id": 35,
				"url": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/related1.jpg",
				"thumbnail": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/related1-150x150.jpg",
				"medium": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/related1-300x200.jpg",
				"large": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/related1-1024x683.jpg",
				"alt": "関連画像1"
			},
			"views": 45,
			"author": {
				"id": 1,
				"name": "管理者"
			}
		},
		{
			"id": 83,
			"title": "関連投稿タイトル2",
			"content": "<p>別の関連投稿内容...</p>",
			"excerpt": "別の関連投稿の抜粋...",
			"slug": "related-post-2",
			"date": {
				"published": "2025-07-03T11:45:00+00:00",
				"published_formatted": "July 3, 2025"
			},
			"featured_image": null,
			"views": 32,
			"author": {
				"id": 2,
				"name": "投稿者"
			}
		}
		// ... 最大5件まで
	]
}
```

### 6. スラッグで投稿のおすすめを取得

**GET** `/wp-json/api/v1/posts/slug/{slug}/recommend`

指定されたスラッグに基づいて、関連する投稿を最大 5 件返します。

**対応形式:**

- 通常のスラッグ: `hello-world`
- 日本語スラッグ: `わからんよ`
- URL エンコード済み: `%e3%82%8f%e3%81%8b%e3%82%89%e3%82%93%e3%82%88`

**動作ロジック:**

1. まず同じカテゴリの投稿を検索
2. 不足分はランダムに投稿を選択
3. 現在の投稿は除外

**レスポンス例:**

```json
{
	"recommended_posts": [
		// 上記と同じ形式で最大5件
	]
}
```

## 注意事項

- `/posts/{id}` と `/posts/slug/{slug}` はビューカウンターを増加させます
- `featured_image` は設定されていない場合 `null` になります
- `/posts/popular` はビュー数の多い順に最大 5 件を返します
- **recommend APIs は同じカテゴリの投稿を優先的に返し、不足分はランダムに選択します**
- **recommend APIs はビューカウンターを増加させません**

## 使用例

### 基本的な投稿取得

```javascript
// 全投稿取得
fetch("/wp-json/api/v1/posts");

// ID で取得（ビュー数+1）
fetch("/wp-json/api/v1/posts/123");

// スラッグで取得（ビュー数+1）
fetch("/wp-json/api/v1/posts/slug/sample-post");

// 日本語スラッグで取得
fetch("/wp-json/api/v1/posts/slug/わからんよ");
```

### おすすめ投稿取得

```javascript
// ID でおすすめ取得
fetch("/wp-json/api/v1/posts/123/recommend");

// スラッグでおすすめ取得
fetch("/wp-json/api/v1/posts/slug/sample-post/recommend");

// 日本語スラッグでおすすめ取得
fetch("/wp-json/api/v1/posts/slug/わからんよ/recommend");
```

### 人気投稿取得

```javascript
// トップ5人気投稿
fetch("/wp-json/api/v1/posts/popular");
```

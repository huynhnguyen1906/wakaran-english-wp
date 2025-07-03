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

## 注意事項

- `/posts/{id}` と `/posts/slug/{slug}` はビューカウンターを増加させます
- `featured_image` は設定されていない場合 `null` になります
- `/posts/popular` はビュー数の多い順に最大 5 件を返します

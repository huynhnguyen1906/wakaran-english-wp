# フィーチャープロジェクト API v1 ドキュメント

## エンドポイント

### 全フィーチャープロジェクトを取得

**GET** `/wp-json/api/v1/feature-projects`

**レスポンス例:**

```json
{
	"projects": [
		{
			"id": 56,
			"title": "Eコマースサイト制作プロジェクト",
			"date": {
				"project_date": "2025-07-10T00:00:00+00:00",
				"project_date_formatted": "July 10, 2025"
			},
			"featured_image": {
				"id": 12,
				"url": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/ecommerce-project.png",
				"thumbnail": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/ecommerce-project-150x150.png",
				"medium": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/ecommerce-project-300x200.png",
				"large": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/ecommerce-project-1024x683.png",
				"alt": "Eコマースプロジェクトのスクリーンショット"
			},
			"youtube_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ"
		},
		{
			"id": 45,
			"title": "コーポレートサイトリニューアル",
			"date": {
				"project_date": "2025-07-05T00:00:00+00:00",
				"project_date_formatted": "July 5, 2025"
			},
			"featured_image": {
				"id": 18,
				"url": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/corporate-site.jpg",
				"thumbnail": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/corporate-site-150x150.jpg",
				"medium": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/corporate-site-300x200.jpg",
				"large": "http://api.wakaran-eng.com/wp-content/uploads/2025/07/corporate-site-1024x683.jpg",
				"alt": "コーポレートサイトのデザイン"
			},
			"youtube_url": null
		},
		{
			"id": 32,
			"title": "モバイルアプリUI/UXデザイン",
			"date": {
				"project_date": "2025-06-28T00:00:00+00:00",
				"project_date_formatted": "June 28, 2025"
			},
			"featured_image": null,
			"youtube_url": "https://www.youtube.com/watch?v=abcd1234567"
		}
	]
}
```

## 注意事項

- フィーチャープロジェクトには 1 つのエンドポイントのみ（全取得）
- `featured_image` は設定されていない場合 `null` になります
- `youtube_url` は設定されていない場合 `null` になります
- タイトル、プロジェクト日付、画像、YouTube URL のシンプルな構造です
- **この API はサーバーサイドからのみ呼び出してください**

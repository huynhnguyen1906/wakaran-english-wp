# Wakaran Engineering WordPress API ドキュメント

これはカスタム REST API エンドポイントを持つヘッドレス WordPress インストールです。投稿とフィーチャープロジェクト用の API を提供します。

## ベース URL

```
http://your-domain.com/wp-json/api/v1/
```

## 利用可能な API

1. [投稿 API](./posts-api.md) - ビューカウンター機能付き WordPress 投稿
2. [フィーチャープロジェクト API](./feature-projects-api.md) - プロジェクトショーケース用カスタム投稿タイプ

## 認証

すべてのエンドポイントは公開アクセス可能です（認証不要）。

## レスポンス形式

すべてのレスポンスは適切な HTTP ステータスコードを持つ JSON 形式です。

## エラーハンドリング

- `404` - リソースが見つかりません
- `403` - アクセス禁止
- `500` - サーバーエラー

エラーレスポンス形式:

```json
{
	"code": "error_code",
	"message": "エラーの説明",
	"data": {
		"status": 404
	}
}
```

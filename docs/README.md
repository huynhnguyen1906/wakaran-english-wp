# Wakaran Engineering WordPress API Documentation

This is a headless WordPress installation with custom REST API endpoints for Posts and Feature Projects.

## Base URL

```
http://your-domain.com/wp-json/api/v1/
```

## Available APIs

1. [Posts API](./posts-api.md) - WordPress blog posts with view counter
2. [Feature Projects API](./feature-projects-api.md) - Custom post type for project showcases

## Authentication

All endpoints are publicly accessible (no authentication required).

## Response Format

All responses are in JSON format with proper HTTP status codes.

## Error Handling

- `404` - Resource not found
- `403` - Access forbidden
- `500` - Server error

Error response format:

```json
{
	"code": "error_code",
	"message": "Error description",
	"data": {
		"status": 404
	}
}
```

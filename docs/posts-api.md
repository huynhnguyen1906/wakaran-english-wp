# Posts API Documentation

## Overview

REST API endpoints for WordPress blog posts with view counter functionality.

## Endpoints

### 1. Get All Posts

**GET** `/posts`

Get all published blog posts.

**Parameters:**

- `per_page` (optional): Number of posts to return. Default: all posts (-1)

**Example Request:**

```
GET /wp-json/api/v1/posts
GET /wp-json/api/v1/posts?per_page=10
```

**Response:**

```json
{
	"posts": [
		{
			"id": 1,
			"title": "Sample Blog Post",
			"content": "<p>Full HTML content of the post...</p>",
			"excerpt": "Short description of the post...",
			"slug": "sample-blog-post",
			"date": {
				"published": "2025-07-03T10:30:00+00:00",
				"published_formatted": "July 3, 2025"
			},
			"featured_image": {
				"id": 15,
				"url": "http://domain.com/wp-content/uploads/2025/07/image.jpg",
				"thumbnail": "http://domain.com/wp-content/uploads/2025/07/image-150x150.jpg",
				"medium": "http://domain.com/wp-content/uploads/2025/07/image-300x200.jpg",
				"large": "http://domain.com/wp-content/uploads/2025/07/image-1024x683.jpg",
				"alt": "Image alt text"
			},
			"views": 25,
			"author": {
				"id": 1,
				"name": "Admin User"
			}
		}
	]
}
```

---

### 2. Get Post by ID

**GET** `/posts/{id}`

Get a single post by ID. This endpoint increments the view counter.

**Parameters:**

- `id` (required): Post ID

**Example Request:**

```
GET /wp-json/api/v1/posts/1
```

**Response:**

```json
{
	"id": 1,
	"title": "Sample Blog Post",
	"content": "<p>Full HTML content of the post...</p>",
	"excerpt": "Short description of the post...",
	"slug": "sample-blog-post",
	"date": {
		"published": "2025-07-03T10:30:00+00:00",
		"published_formatted": "July 3, 2025"
	},
	"featured_image": {
		"id": 15,
		"url": "http://domain.com/wp-content/uploads/2025/07/image.jpg",
		"thumbnail": "http://domain.com/wp-content/uploads/2025/07/image-150x150.jpg",
		"medium": "http://domain.com/wp-content/uploads/2025/07/image-300x200.jpg",
		"large": "http://domain.com/wp-content/uploads/2025/07/image-1024x683.jpg",
		"alt": "Image alt text"
	},
	"views": 26,
	"author": {
		"id": 1,
		"name": "Admin User"
	}
}
```

---

### 3. Get Post by Slug

**GET** `/posts/slug/{slug}`

Get a single post by slug. This endpoint increments the view counter.

**Parameters:**

- `slug` (required): Post slug

**Example Request:**

```
GET /wp-json/api/v1/posts/slug/sample-blog-post
```

**Response:**
Same as "Get Post by ID" above.

---

### 4. Get Popular Posts

**GET** `/posts/popular`

Get top 5 posts with highest view counts.

**Example Request:**

```
GET /wp-json/api/v1/posts/popular
```

**Response:**

```json
{
	"posts": [
		{
			"id": 3,
			"title": "Most Popular Post",
			"content": "<p>Content...</p>",
			"excerpt": "Excerpt...",
			"slug": "most-popular-post",
			"date": {
				"published": "2025-07-01T15:20:00+00:00",
				"published_formatted": "July 1, 2025"
			},
			"featured_image": null,
			"views": 150,
			"author": {
				"id": 1,
				"name": "Admin User"
			}
		},
		{
			"id": 1,
			"title": "Second Popular Post",
			"content": "<p>Content...</p>",
			"excerpt": "Excerpt...",
			"slug": "second-popular-post",
			"date": {
				"published": "2025-07-02T09:15:00+00:00",
				"published_formatted": "July 2, 2025"
			},
			"featured_image": {
				"id": 20,
				"url": "http://domain.com/wp-content/uploads/2025/07/popular.jpg",
				"thumbnail": "http://domain.com/wp-content/uploads/2025/07/popular-150x150.jpg",
				"medium": "http://domain.com/wp-content/uploads/2025/07/popular-300x200.jpg",
				"large": "http://domain.com/wp-content/uploads/2025/07/popular-1024x683.jpg",
				"alt": "Popular post image"
			},
			"views": 89,
			"author": {
				"id": 1,
				"name": "Admin User"
			}
		}
	]
}
```

## Error Responses

### Post Not Found

```json
{
	"code": "not_found",
	"message": "Post not found",
	"data": {
		"status": 404
	}
}
```

## Notes

- **View Counter**: Only `/posts/{id}` and `/posts/slug/{slug}` endpoints increment view counters
- **Featured Image**: Returns `null` if no featured image is set
- **Content**: HTML content is processed through WordPress filters
- **Ordering**: Default order is by date (newest first), popular posts are ordered by view count (highest first)

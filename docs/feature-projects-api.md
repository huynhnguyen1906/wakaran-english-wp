# Feature Projects API Documentation

## Overview

REST API endpoints for Feature Projects - a custom post type designed for showcasing project portfolios.

## Endpoints

### 1. Get All Feature Projects

**GET** `/feature-projects`

Get all published feature projects.

**Example Request:**

```
GET /wp-json/api/v1/feature-projects
```

**Response:**

```json
{
	"feature_projects": [
		{
			"id": 5,
			"title": "E-commerce Website Redesign",
			"date": {
				"published": "2025-07-03T14:30:00+00:00",
				"published_formatted": "July 3, 2025"
			},
			"image": {
				"id": 25,
				"url": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project.jpg",
				"thumbnail": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project-150x150.jpg",
				"medium": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project-300x200.jpg",
				"large": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project-1024x683.jpg",
				"alt": "E-commerce website mockup"
			}
		},
		{
			"id": 4,
			"title": "Mobile App Development",
			"date": {
				"published": "2025-07-02T11:15:00+00:00",
				"published_formatted": "July 2, 2025"
			},
			"image": {
				"id": 23,
				"url": "http://domain.com/wp-content/uploads/2025/07/mobile-app.png",
				"thumbnail": "http://domain.com/wp-content/uploads/2025/07/mobile-app-150x150.png",
				"medium": "http://domain.com/wp-content/uploads/2025/07/mobile-app-300x200.png",
				"large": "http://domain.com/wp-content/uploads/2025/07/mobile-app-1024x683.png",
				"alt": "Mobile app interface"
			}
		},
		{
			"id": 3,
			"title": "Corporate Branding Package",
			"date": {
				"published": "2025-07-01T16:45:00+00:00",
				"published_formatted": "July 1, 2025"
			},
			"image": null
		}
	]
}
```

---

### 2. Get Feature Project by ID

**GET** `/feature-projects/{id}`

Get a single feature project by ID.

**Parameters:**

- `id` (required): Feature Project ID

**Example Request:**

```
GET /wp-json/api/v1/feature-projects/5
```

**Response:**

```json
{
	"id": 5,
	"title": "E-commerce Website Redesign",
	"date": {
		"published": "2025-07-03T14:30:00+00:00",
		"published_formatted": "July 3, 2025"
	},
	"image": {
		"id": 25,
		"url": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project.jpg",
		"thumbnail": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project-150x150.jpg",
		"medium": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project-300x200.jpg",
		"large": "http://domain.com/wp-content/uploads/2025/07/ecommerce-project-1024x683.jpg",
		"alt": "E-commerce website mockup"
	}
}
```

## Field Descriptions

### Response Fields

| Field                      | Type        | Description                                   |
| -------------------------- | ----------- | --------------------------------------------- |
| `id`                       | integer     | Unique identifier for the feature project     |
| `title`                    | string      | Project title                                 |
| `date.published`           | string      | ISO 8601 formatted publication date           |
| `date.published_formatted` | string      | Human-readable publication date               |
| `image`                    | object/null | Featured image information or null if not set |
| `image.id`                 | integer     | WordPress attachment ID                       |
| `image.url`                | string      | Full-size image URL                           |
| `image.thumbnail`          | string      | Thumbnail size URL (150x150)                  |
| `image.medium`             | string      | Medium size URL (300x200)                     |
| `image.large`              | string      | Large size URL (1024x683)                     |
| `image.alt`                | string      | Image alt text for accessibility              |

## Error Responses

### Feature Project Not Found

```json
{
	"code": "not_found",
	"message": "Feature Project not found",
	"data": {
		"status": 404
	}
}
```

## Notes

- **Simplified Structure**: Feature Projects only contain title, date, and image (no content, excerpt, or author)
- **No View Counter**: Unlike Posts API, Feature Projects don't track views
- **Image Required**: Projects are designed to showcase visual work, so images are expected but not required
- **Ordering**: Default order is by date (newest first)
- **No Slug**: Feature Projects don't use slugs - access only by ID

## Admin Interface

In WordPress admin, Feature Projects appear as a separate menu item with:

- Custom date meta box in sidebar
- Featured image support
- Simplified editor (no content area, excerpt, or slug)
- Custom icon and labels

# API Examples and Testing

## Quick Test Commands

Use these commands to test the APIs locally.

### Posts API Tests

```bash
# Get all posts
curl "http://localhost/wp-json/api/v1/posts"

# Get posts with limit
curl "http://localhost/wp-json/api/v1/posts?per_page=5"

# Get post by ID (increments view counter)
curl "http://localhost/wp-json/api/v1/posts/1"

# Get post by slug (increments view counter)
curl "http://localhost/wp-json/api/v1/posts/slug/hello-world"

# Get popular posts (top 5 by views)
curl "http://localhost/wp-json/api/v1/posts/popular"
```

### Feature Projects API Tests

```bash
# Get all feature projects
curl "http://localhost/wp-json/api/v1/feature-projects"

# Get feature project by ID
curl "http://localhost/wp-json/api/v1/feature-projects/1"
```

## Integration Examples

### JavaScript/React Example

```javascript
// Fetch all posts
const fetchPosts = async () => {
	try {
		const response = await fetch("/wp-json/api/v1/posts");
		const data = await response.json();
		return data.posts;
	} catch (error) {
		console.error("Error fetching posts:", error);
	}
};

// Fetch single post (increments view)
const fetchPost = async (id) => {
	try {
		const response = await fetch(`/wp-json/api/v1/posts/${id}`);
		const post = await response.json();
		return post;
	} catch (error) {
		console.error("Error fetching post:", error);
	}
};

// Fetch popular posts
const fetchPopularPosts = async () => {
	try {
		const response = await fetch("/wp-json/api/v1/posts/popular");
		const data = await response.json();
		return data.posts;
	} catch (error) {
		console.error("Error fetching popular posts:", error);
	}
};

// Fetch feature projects
const fetchFeatureProjects = async () => {
	try {
		const response = await fetch("/wp-json/api/v1/feature-projects");
		const data = await response.json();
		return data.feature_projects;
	} catch (error) {
		console.error("Error fetching feature projects:", error);
	}
};
```

### PHP Example

```php
// Get posts data
$posts_response = wp_remote_get('http://your-domain.com/wp-json/api/v1/posts');
$posts_data = json_decode(wp_remote_retrieve_body($posts_response), true);
$posts = $posts_data['posts'];

// Get feature projects data
$projects_response = wp_remote_get('http://your-domain.com/wp-json/api/v1/feature-projects');
$projects_data = json_decode(wp_remote_retrieve_body($projects_response), true);
$projects = $projects_data['feature_projects'];
```

## Response Structure Summary

### Posts API Response Structure

```
{
  posts: [
    {
      id: number,
      title: string,
      content: string (HTML),
      excerpt: string,
      slug: string,
      date: {
        published: string (ISO 8601),
        published_formatted: string
      },
      featured_image: {
        id: number,
        url: string,
        thumbnail: string,
        medium: string,
        large: string,
        alt: string
      } | null,
      views: number,
      author: {
        id: number,
        name: string
      }
    }
  ]
}
```

### Feature Projects API Response Structure

```
{
  feature_projects: [
    {
      id: number,
      title: string,
      date: {
        published: string (ISO 8601),
        published_formatted: string
      },
      image: {
        id: number,
        url: string,
        thumbnail: string,
        medium: string,
        large: string,
        alt: string
      } | null
    }
  ]
}
```

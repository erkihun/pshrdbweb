# API Documentation

Base URL: `https://your-domain.tld`

## Authentication (Sanctum)

### POST `/api/auth/login`

Request:

```json
{
  "email": "admin@example.com",
  "password": "password",
  "device_name": "mobile-app"
}
```

Response:

```json
{
  "token": "<token>",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "roles": ["Admin"],
    "permissions": ["manage posts", "manage services"]
  }
}
```

Use the token in the `Authorization` header for protected endpoints:

```
Authorization: Bearer <token>
```

## Public Endpoints

### GET `/api/news`
Query params: `q` (optional)

### GET `/api/announcements`
Query params: `q` (optional)

### GET `/api/services`

### GET `/api/downloads`
Query params: `q` (optional)

## Tickets

### POST `/api/tickets`

Request:

```json
{
  "name": "Test User",
  "email": "contact@example.com",
  "phone": "+251900000000",
  "subject": "Help needed",
  "message": "Please assist with this request."
}
```

Response:

```json
{
  "reference_code": "TKT-ABCDEFGH"
}
```

### GET `/api/tickets/{reference_code}`

Response:

```json
{
  "data": {
    "reference_code": "TKT-ABCDEFGH",
    "status": "open",
    "admin_reply": null,
    "replied_at": null,
    "created_at": "2025-12-23T12:00:00Z"
  }
}
```

## Admin (Token + Permissions)

All endpoints below require `Authorization: Bearer <token>` and proper permissions.

### Posts
- GET `/api/admin/posts`
- POST `/api/admin/posts`
- GET `/api/admin/posts/{id}`
- PUT `/api/admin/posts/{id}`
- DELETE `/api/admin/posts/{id}`

### Services
- GET `/api/admin/services`
- POST `/api/admin/services`
- GET `/api/admin/services/{id}`
- PUT `/api/admin/services/{id}`
- DELETE `/api/admin/services/{id}`

### Documents
- GET `/api/admin/documents`
- POST `/api/admin/documents`
- GET `/api/admin/documents/{id}`
- PUT `/api/admin/documents/{id}`
- DELETE `/api/admin/documents/{id}`

### Tickets
- GET `/api/admin/tickets`
- GET `/api/admin/tickets/{id}`
- PUT `/api/admin/tickets/{id}`
- DELETE `/api/admin/tickets/{id}`

## Rate Limiting

- `/api/*` uses `throttle:api` (60 requests/minute by IP)
- `/api/tickets` uses `throttle:tickets` (10 requests/minute by IP)

## Notes

- Locale can be set via `?locale=am|en` or `Accept-Language` header.
- Public list endpoints return paginated data where applicable.

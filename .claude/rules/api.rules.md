# API Rules

## API Standards

- RESTful conventions
- JSON response standard
- Proper HTTP status code

---

# Response Format

## Success

```json
{
  "success": true,
  "data": {}
}
```

## Error

```json
{
  "success": false,
  "error_code": "MODULE_XXX",
  "message": "Error message"
}
```

> `error_code` là mã lỗi định danh theo format `MODULE_NNN` (VD: `AUTH_001`, `POST_002`).
> Phải luôn có `error_code` trong error response để client xử lý phân loại lỗi.

## Pagination

- API list phải paginate
- Không trả toàn bộ dataset lớn

## Security

- Authentication middleware
- Rate limiting
- API validation

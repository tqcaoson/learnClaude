# Post Management Skill

## Mục tiêu

Quản lý toàn bộ vòng đời bài viết.

---

# Chức năng

- Tạo bài viết
- Chỉnh sửa bài viết
- Xóa bài viết
- Draft bài viết
- Upload attachment
- Tags/Topics
- Reactions
- Edit history

---

# Quy tắc nghiệp vụ

- User chỉ được sửa bài của mình
- Moderator/Admin có thể xử lý bài vi phạm
- Soft delete thay vì hard delete
- Nội dung phải được sanitize

---

# Tối ưu hiệu năng

- Pagination
- Lazy loading
- Cache trending posts
- Queue xử lý upload

---

# Cấu trúc gợi ý

- PostService
- PostRepository
- PostPolicy
- CreatePostAction
- UpdatePostAction

---

# Database liên quan

## posts

- id
- board_id
- user_id
- title
- content
- status (draft/published/hidden)
- slug (unique, SEO-friendly)
- views_count (default 0)
- deleted_at (soft delete)

## reactions

- id
- target_type
- target_id
- reaction_type

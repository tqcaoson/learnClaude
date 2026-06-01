# Comment Skill

## Mục tiêu

Quản lý hệ thống bình luận.

---

# Chức năng

- Tạo bình luận
- Nested comments
- Mention user
- Reactions
- Edit/Delete comment

---

# Quy tắc

- User chỉ sửa comment của mình
- Moderator/Admin có thể xóa comment
- Hỗ trợ reply nhiều cấp

---

# Tối ưu

- Lazy load comments
- Tree structure optimization với parent_id + closure table nếu cần
- Queue notify mention

---

# Database liên quan

## comments

- id
- post_id
- user_id
- parent_id (nullable — null nếu là top-level comment)
- content
- deleted_at (soft delete)

---

# Security

- Sanitize HTML
- Anti spam
- Rate limit comment

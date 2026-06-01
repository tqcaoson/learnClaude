# SRS.md – Tài liệu đặc tả yêu cầu phần mềm

## Dự án: Nền tảng giao lưu và chia sẻ kiến thức trực tuyến

---

# 1. Giới thiệu

## 1.1 Mục đích

Tài liệu SRS (Software Requirements Specification) mô tả toàn bộ yêu cầu chức năng và phi chức năng của hệ thống nền tảng giao lưu, chia sẻ kiến thức trực tuyến.

Mục tiêu của hệ thống:

- Cho phép người dùng đăng tải và chia sẻ kiến thức.
- Tạo môi trường thảo luận và tương tác cộng đồng.
- Hỗ trợ kiểm duyệt nội dung.
- Đảm bảo hiệu năng, bảo mật và khả năng mở rộng.

Tài liệu này được sử dụng cho:

- Phân tích nghiệp vụ
- Thiết kế hệ thống
- Phát triển phần mềm
- Kiểm thử
- Bảo trì hệ thống

---

## 1.2 Phạm vi hệ thống

Hệ thống là một nền tảng web cho phép:

- Người dùng đọc bài viết
- Đăng bài viết
- Bình luận và tương tác
- Quản lý hồ sơ cá nhân
- Quản trị và kiểm duyệt nội dung

Hệ thống hỗ trợ nhiều cấp quyền người dùng khác nhau nhằm đảm bảo quản lý cộng đồng hiệu quả.

---

## 1.3 Định nghĩa và thuật ngữ

| Thuật ngữ | Ý nghĩa                   |
| --------- | ------------------------- |
| Guest     | Người dùng chưa đăng nhập |
| Member    | Thành viên đã đăng ký     |
| Moderator | Người kiểm duyệt nội dung |
| Admin     | Quản trị hệ thống         |
| Post      | Bài viết                  |
| Comment   | Bình luận                 |
| Profile   | Trang cá nhân             |

---

# 2. Tổng quan hệ thống

## 2.1 Kiến trúc tổng quát

Hệ thống gồm các thành phần:

- Frontend (Web UI)
- Backend API
- Database
- Authentication Service
- File Storage

---

## 2.2 Phân quyền người dùng

### Guest

Quyền hạn:

- Xem nội dung công khai
- Tìm kiếm bài viết
- Xem hồ sơ công khai

Không được phép:

- Đăng bài
- Bình luận
- Tương tác

---

### Member

Quyền hạn:

- Đăng bài viết
- Chỉnh sửa bài viết của bản thân
- Xóa bài viết của bản thân
- Bình luận
- Quản lý hồ sơ cá nhân
- Upload ảnh đại diện

---

### Moderator

Bao gồm toàn bộ quyền của Member và thêm:

- Kiểm duyệt bài viết
- Ẩn/Xóa bài vi phạm
- Khóa bình luận
- Cảnh cáo người dùng

---

### Admin

Bao gồm toàn bộ quyền của Moderator và thêm:

- Quản lý người dùng
- Phân quyền hệ thống
- Xem thống kê
- Cấu hình hệ thống

---

# 3. Yêu cầu chức năng

# 3.1 Chức năng xác thực

## 3.1.1 Đăng ký tài khoản

Người dùng có thể:

- Đăng ký bằng email
- Đặt mật khẩu
- Xác minh email

Ràng buộc:

- Email không được trùng
- Mật khẩu tối thiểu 8 ký tự

---

## 3.1.2 Đăng nhập

Hệ thống hỗ trợ:

- Đăng nhập email/password
- Ghi nhớ đăng nhập
- Đăng xuất

---

## 3.1.3 Quên mật khẩu

Người dùng có thể:

- Yêu cầu reset mật khẩu
- Nhận email khôi phục
- Đặt mật khẩu mới

---

# 3.2 Quản lý bài viết

## 3.2.1 Tạo bài viết

Member có thể:

- Tạo bài viết mới
- Chọn danh mục
- Soạn nội dung rich text
- Upload ảnh minh họa

---

## 3.2.2 Chỉnh sửa bài viết

Người dùng chỉ được sửa bài viết của chính mình.

Moderator và Admin có thể sửa bài viết vi phạm khi cần thiết.

---

## 3.2.3 Xóa bài viết

- Member xóa bài của bản thân
- Moderator/Admin có quyền xóa mọi bài viết

---

## 3.2.4 Danh sách bài viết

Hệ thống hỗ trợ:

- Phân trang
- Tìm kiếm
- Lọc theo danh mục
- Sắp xếp theo:

  - Mới nhất
  - Nhiều lượt xem
  - Nhiều tương tác

---

# 3.3 Bình luận

## 3.3.1 Tạo bình luận

Member có thể:

- Bình luận bài viết
- Trả lời bình luận khác

---

## 3.3.2 Quản lý bình luận

Người dùng có thể:

- Chỉnh sửa bình luận của mình
- Xóa bình luận của mình

Moderator/Admin:

- Xóa bình luận vi phạm
- Khóa bình luận

---

# 3.4 Hồ sơ người dùng

## 3.4.1 Quản lý thông tin cá nhân

Người dùng có thể cập nhật:

- Tên hiển thị
- Ảnh đại diện
- Giới thiệu cá nhân
- Liên kết mạng xã hội

---

## 3.4.2 Trang cá nhân

Hiển thị:

- Danh sách bài viết
- Thông tin người dùng
- Hoạt động gần đây

---

# 3.5 Hệ thống thông báo

Thông báo khi:

- Có người bình luận
- Có người trả lời bình luận
- Bài viết bị kiểm duyệt
- Nhận cảnh báo từ Moderator

---

# 3.6 Quản trị hệ thống

## 3.6.1 Quản lý người dùng

Admin có thể:

- Khóa tài khoản
- Mở khóa tài khoản
- Phân quyền

---

## 3.6.2 Thống kê hệ thống

Bao gồm:

- Số lượng người dùng
- Số bài viết
- Lượt truy cập
- Nội dung bị báo cáo

---

# 4. Yêu cầu phi chức năng

# 4.1 Hiệu năng

Hệ thống cần đáp ứng:

- Thời gian phản hồi < 3 giây
- Hỗ trợ tối thiểu 10.000 người dùng đồng thời
- Tối ưu truy vấn database

---

# 4.2 Bảo mật

Bao gồm:

- Hash mật khẩu
- CSRF protection
- XSS protection
- Rate limiting
- JWT hoặc Session Authentication

---

# 4.3 Khả năng mở rộng

Hệ thống cần:

- Tách service khi cần
- Hỗ trợ cache

---

# 4.4 Khả năng bảo trì

Codebase cần:

- Theo chuẩn coding convention
- Có test
- Có tài liệu API
- Dễ mở rộng module

---

# 4.5 Tính khả dụng

Mục tiêu uptime:

- 99.9%

Có cơ chế:

- Backup dữ liệu
- Monitoring
- Logging

---

# 5. Thiết kế dữ liệu sơ bộ

## 5.1 Bảng users

| Field    | Type   |
| -------- | ------ |
| id       | bigint |
| name     | string |
| email    | string |
| password | string |
| role     | enum   |
| avatar   | string |

---

## 5.2 Bảng posts

| Field      | Type      |
| ---------- | --------- |
| id         | bigint    |
| user_id    | bigint    |
| title      | string    |
| content    | text      |
| status     | enum      |
| created_at | timestamp |

---

## 5.3 Bảng comments

| Field   | Type   |
| ------- | ------ |
| id      | bigint |
| post_id | bigint |
| user_id | bigint |
| content | text   |

---

# 6. API sơ bộ

## Authentication

| Method | Endpoint                            |
| ------ | ----------------------------------- |
| POST   | /api/auth/register                  |
| POST   | /api/auth/login                     |
| POST   | /api/auth/logout                    |
| POST   | /api/auth/forgot-password           |
| POST   | /api/auth/reset-password            |
| GET    | /api/auth/oauth/{provider}/redirect |
| GET    | /api/auth/oauth/{provider}/callback |

---

## Posts

| Method | Endpoint        |
| ------ | --------------- |
| GET    | /api/posts      |
| POST   | /api/posts      |
| PUT    | /api/posts/{id} |
| DELETE | /api/posts/{id} |

---

## Comments

| Method | Endpoint           |
| ------ | ------------------ |
| POST   | /api/comments      |
| DELETE | /api/comments/{id} |

---

# 7. Use Case chính

## Guest

- Xem bài viết
- Tìm kiếm nội dung
- Đăng ký tài khoản

---

## Member

- Đăng bài
- Bình luận
- Quản lý hồ sơ

---

## Moderator

- Kiểm duyệt nội dung
- Xử lý báo cáo

---

## Admin

- Quản trị hệ thống
- Quản lý phân quyền

---

# 8. Ràng buộc kỹ thuật

## Backend

Đề xuất:

- Laravel
- REST API
- MySQL/PostgreSQL

---

## Frontend

Đề xuất:

- Vue.js hoặc React
- Responsive UI

---

## Hạ tầng

Đề xuất:

- Docker
- Nginx
- CI/CD Pipeline

---

# 9. Hướng phát triển tương lai

Các tính năng mở rộng:

- AI gợi ý nội dung
- Hệ thống reputation
- Bookmark bài viết
- Like/Reaction
- Mobile app

---

# 10. Kết luận

Tài liệu SRS này mô tả các yêu cầu nền tảng cho hệ thống giao lưu và chia sẻ kiến thức trực tuyến.

Tài liệu sẽ tiếp tục được cập nhật trong quá trình:

- Phân tích nghiệp vụ
- Thiết kế hệ thống
- Phát triển tính năng
- Kiểm thử và vận hành hệ thống

# Backend Agent

## Vai trò

Chịu trách nhiệm phát triển backend system theo kiến trúc sạch, dễ mở rộng và bảo trì.

---

# Trách nhiệm chính

- Thiết kế API
- Xử lý business logic
- Authorization
- Validation
- Queue jobs
- Service layer
- Database interaction

---

# Quy tắc bắt buộc

- Không viết business logic trong controller
- Sử dụng Service/Action class
- Validate bằng FormRequest
- Dùng Policy/Gate cho authorization
- Không query DB trực tiếp trong view

---

# Coding Style

- Thin Controller
- Fat Service
- Explicit naming
- SOLID principles
- Dependency Injection

---

# Ưu tiên

1. Security
2. Maintainability
3. Performance
4. Readability

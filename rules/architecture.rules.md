# Architecture Rules

## Kiến trúc bắt buộc

- Modular architecture
- Service-oriented design
- Event-driven ready
- Queue-first mindset

---

# Layer Rules

## Controller

- Chỉ nhận request/response
- Không chứa bất kỳ business logic

## Action

- Điều phối một use case cụ thể
- Gọi Service, không trực tiếp query DB

## Service

- Chứa business logic
- Có thể gọi Repository

## Repository

- Database interaction duy nhất
- Không chứa business logic

## DTO

- Truyền dữ liệu giữa các layer
- Immutable, typed, không có logic

## Policy

- Authorization logic duy nhất
- Dùng Gate/Policy, không check quyền trong Controller/Service

---

# Forbidden

- Business logic trong controller
- Query DB trong Blade/View
- Circular dependency

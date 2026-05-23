# Laravel Architecture

## Recommended Structure

```text
app/
 ├── Actions/
 ├── DTOs/
 ├── Enums/
 ├── Events/
 ├── Exceptions/
 ├── Http/
 ├── Jobs/
 ├── Listeners/
 ├── Models/
 ├── Notifications/
 ├── Policies/
 ├── Repositories/
 ├── Services/
 └── Traits/
```

## Architectural Principles

- Thin Controller
- Service Layer
- Repository Pattern
- DTO usage
- Event-driven design

## Queue Usage

- Dùng queue cho:
- Email sending
- Search indexing
- File processing

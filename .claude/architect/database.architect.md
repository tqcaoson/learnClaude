# Database Architecture

## Database đề xuất

- MySQL
- PostgreSQL

---

# Thiết kế chính

## Category

└── Board
└── Post (posts: id, board_id, user_id, title, content, status, slug, views_count, deleted_at)
└── Comment (comments: id, post_id, user_id, parent_id, content, deleted_at)

---

## Authentication

- users
- login_attempts (email, ip_address, attempts, locked_until)
- user_sessions (user_id, session_id, ip_address, user_agent, last_activity)
- login_audits (user_id, email, login_status, ip_address, failure_reason, login_at)

---

# Chiến lược

- Proper indexing
- Read replicas
- Query optimization
- Backup & recovery

---

# Cache Strategy

Cache cho:

- Sessions
- Trending posts
- Search suggestions
- Category tree

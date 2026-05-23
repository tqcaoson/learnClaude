# Security Rules

## Mandatory Rules

- Validate mọi input
- Escape mọi output
- Không trust request client
- Authorization cho mọi action nhạy cảm

---

# Authentication

- Password hash bắt buộc
- Session timeout
- Rate limit login
- Secure cookie only

---

# Upload Rules

- Validate MIME type
- Chặn executable file
- Limit file size

---

# Forbidden

- Không lưu plain password
- Không expose stack trace production
- Không disable CSRF

# Authentication Skill

## Mục tiêu

Quản lý toàn bộ logic xác thực và tài khoản người dùng.

---

# Chức năng chính

- Đăng ký tài khoản
- Đăng nhập (Email/Password + OAuth)
- Đăng xuất
- Quên mật khẩu
- Reset mật khẩu
- Xác minh email
- Xác thực 2 lớp (2FA)
- Quản lý session/device

---

# Yêu cầu bảo mật

- Hash password bằng bcrypt hoặc argon2id
- CSRF Protection
- Rate limiting login (max 5 lần / 15 phút lock)
- Secure Cookie (HttpOnly, Secure, SameSite=Lax)
- Session regenerate sau login thành công
- Session expiration (120 phút active / 30 ngày remember me)
- Device tracking qua bảng user_sessions
- Audit log mọi login success/failed vào login_audits

---

# Laravel Components đề xuất

- FormRequest (LoginRequest, OAuthCallbackRequest)
- Middleware (EnsureAuthenticated)
- Policies
- Sanctum hoặc Passport (API token)
- Laravel Socialite (OAuth Google/Facebook)
- Laravel Fortify (2FA ready)

---

# Best Practices

- Không lưu plain password
- Validate input đầy đủ trước khi query DB
- Không expose lỗi nhạy cảm — dùng error_code chuẩn
- Không trả về thông tin user không cần thiết
- Luôn verify email khi đăng ký
- Session fixation prevention sau mỗi login

---

# API chính

| Method | Endpoint                            | Mô tả            |
| ------ | ----------------------------------- | ---------------- |
| POST   | /api/auth/register                  | Đăng ký          |
| POST   | /api/auth/login                     | Đăng nhập        |
| POST   | /api/auth/logout                    | Đăng xuất        |
| POST   | /api/auth/forgot-password           | Yêu cầu reset    |
| POST   | /api/auth/reset-password            | Đặt mật khẩu mới |
| GET    | /api/auth/oauth/{provider}/redirect | OAuth redirect   |
| GET    | /api/auth/oauth/{provider}/callback | OAuth callback   |

---

# Error Codes

| Error Code | Mô tả                |
| ---------- | -------------------- |
| AUTH_001   | Invalid credentials  |
| AUTH_002   | Account locked       |
| AUTH_003   | Email not verified   |
| AUTH_004   | Too many attempts    |
| AUTH_005   | OAuth provider error |
| AUTH_006   | Account disabled     |

---

# Luồng xử lý chuẩn

## Register

1. Validate input
2. Hash password
3. Tạo user
4. Gửi email verify
5. Trả response thành công

## Login (Email/Password)

1. Validate input
2. Check rate limit / login attempts
3. Tìm user theo email
4. Verify password hash
5. Check account status (active/disabled/banned)
6. Check email verified
7. Check 2FA enabled → chuyển OTP flow nếu bật
8. Generate session / token
9. Regenerate session ID
10. Reset login attempts
11. Update last_login_at
12. Dispatch LoginSuccessEvent
13. Trả access token + user info

## OAuth Login

1. Redirect đến provider (Google/Facebook)
2. Nhận callback + validate provider token
3. Tìm user theo provider_id hoặc email
4. Tạo user mới nếu chưa tồn tại
5. Tạo authenticated session
6. Dispatch LoginSuccessEvent
7. Redirect homepage

---

# Cấu trúc thư mục

```text
app/
 ├── Actions/Auth/
 │    ├── LoginAction.php
 │    ├── LogoutAction.php
 │    └── OAuthLoginAction.php
 ├── DTOs/Auth/
 │    ├── LoginDTO.php
 │    └── OAuthUserDTO.php
 ├── Services/Auth/
 │    ├── AuthenticationService.php
 │    ├── SessionService.php
 │    ├── OAuthService.php
 │    └── LoginAttemptService.php
 ├── Repositories/Auth/
 │    ├── UserRepository.php
 │    ├── LoginAttemptRepository.php
 │    └── SessionRepository.php
 └── Http/
      ├── Controllers/Auth/
      ├── Requests/Auth/
      └── Middleware/
```

---

# Rủi ro cần tránh

- Brute force attack → rate limit + temporary lockout
- Session hijacking → regenerate session ID sau login
- Credential stuffing → rate limit per IP + per email
- Token leak → HttpOnly cookie, không expose trong JS
- Session fixation → session()->invalidate() + regenerateToken()

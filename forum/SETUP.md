# Community Forum - Setup Guide (Day 1-2)

## ✅ Completed: Project Init + Authentication

This guide covers the setup and quick start for the **Community Forum MVP** after Day 1 & 2 implementation.

---

## 📋 Prerequisites

- **PHP 8.2+** (with extensions: curl, mbstring, sqlite3, xml)
- **MySQL 5.7+** or **PostgreSQL 10+**
- **Composer** (package manager)
- **Git** (version control)

---

## 🚀 Quick Start (5 minutes)

### 1. Install Dependencies
```bash
cd /home/deck/Downloads/learnClaude/forum
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum_db
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

**Create database** (MySQL):
```sql
CREATE DATABASE forum_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Run Seeder (Optional - creates test users)
```bash
php artisan db:seed
```

### 6. Start Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
php artisan test tests/Feature/AuthenticationTest.php
php artisan test tests/Feature/PostTest.php
php artisan test tests/Feature/CommentTest.php
```

### Run with Coverage
```bash
php artisan test --coverage
```

---

## 🔐 Authentication Features (Day 2)

### Registration
1. Go to http://localhost:8000/register
2. Fill in email, password (min 8 chars), and optional name
3. Submit form
4. Get redirected to email verification page

### Email Verification
1. After registration, check server logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```
2. Find line: `Email verification token for user@example.com: TOKEN_HERE`
3. Visit: `http://localhost:8000/email/verify/TOKEN_HERE`
4. Success! Email is verified

### Login
1. Go to http://localhost:8000/login
2. Enter email and password
3. Optional: Check "Remember me" for 30-day persistence
4. Submit form
5. Redirected to posts page if successful

### Features
✅ Password hashing (bcrypt)
✅ Email verification required
✅ Rate limiting (5 attempts/15 min)
✅ Session management
✅ "Remember me" functionality
✅ CSRF protection
✅ Input validation

---

## 🧑‍💻 Developer Features

### Test User (from seeder)
```
Email: test@example.com
Password: password
(Already email verified)
```

### Tinker Shell (Laravel REPL)
```bash
php artisan tinker

# Create user
$user = App\Models\User::factory()->create();

# Find user
$user = App\Models\User::find(1);

# Check authentication
auth()->user()
```

### View Logs
```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Email verification tokens appear here
```

### Clear Cache/Sessions
```bash
php artisan cache:clear
php artisan config:clear
php artisan session:clean
```

---

## 📂 Project Structure

```
forum/
├── app/
│   ├── Actions/           # Use case classes
│   ├── DTOs/              # Data transfer objects
│   ├── Exceptions/        # Custom exceptions
│   ├── Http/
│   │   ├── Controllers/   # Controller classes
│   │   ├── Middleware/    # HTTP middleware
│   │   └── Requests/      # Form validation
│   ├── Models/            # Eloquent models
│   ├── Policies/          # Authorization policies
│   ├── Repositories/      # Database access
│   ├── Services/          # Business logic
│   └── Providers/         # Service providers
├── database/
│   ├── migrations/        # Database migrations
│   ├── factories/         # Model factories
│   └── seeders/          # Data seeders
├── resources/
│   └── views/            # Blade templates
│       ├── auth/         # Auth templates
│       ├── posts/        # Post templates
│       ├── comments/     # Comment templates
│       └── layouts/      # Layout templates
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
├── tests/
│   ├── Feature/          # Feature tests
│   └── Unit/             # Unit tests
├── config/               # Configuration files
├── storage/              # Logs and cache
└── public/               # Web root
```

---

## 🔗 Routes (Day 2 Complete)

### Authentication Routes
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/register` | Show registration form | Guest |
| POST | `/register` | Submit registration | Guest |
| GET | `/login` | Show login form | Guest |
| POST | `/login` | Submit login | Guest |
| POST | `/logout` | Log out user | Auth |
| GET | `/email/verify-sent` | Verification page | Any |
| GET | `/email/verify/:token` | Verify email | Any |

### Post Routes (Day 3 - Next)
| GET | `/posts` | List all posts | Any |
| GET | `/posts/:id` | View single post | Any |
| GET | `/posts/create` | Create form | Auth |
| POST | `/posts` | Store new post | Auth |
| GET | `/posts/:id/edit` | Edit form | Auth (owner) |
| PATCH | `/posts/:id` | Update post | Auth (owner) |
| DELETE | `/posts/:id` | Delete post | Auth (owner) |

### Comment Routes (Day 4 - Next)
| POST | `/posts/:id/comments` | Add comment | Auth |
| DELETE | `/comments/:id` | Delete comment | Auth (owner) |

---

## 🐛 Troubleshooting

### Issue: Database connection error
**Solution:**
```bash
# Check database credentials in .env
# Verify MySQL/PostgreSQL is running
# Create database manually if needed
mysql -u root -p
CREATE DATABASE forum_db;
```

### Issue: Permission denied (artisan)
**Solution:**
```bash
chmod +x artisan
```

### Issue: Class not found
**Solution:**
```bash
composer dump-autoload
```

### Issue: Email verification token not found
**Solution:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Look for: "Email verification token for..."
```

### Issue: Rate limiting not working
**Solution:**
- Check that login_attempts table was created: `php artisan migrate`
- Manually reset: `php artisan tinker -> App\Models\LoginAttempt::truncate()`

---

## 📝 Environment Variables

Key `.env` settings:

```env
# App
APP_NAME="Community Forum"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=forum_db
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail (logs to console in dev)
MAIL_MAILER=log
```

---

## ✨ Next Steps

**Day 3**: Post Management (Create, Read, Update, Delete)
- Implement PostController fully
- Create/update/delete posts
- Post ownership validation

**Day 4**: Comments + UI Polish
- Comment CRUD operations
- Better styling with Bootstrap
- Responsive design

**Day 5**: Testing & Deployment
- Complete test coverage
- Performance optimization
- Deployment preparation

---

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templating](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Testing](https://laravel.com/docs/testing)
- [Authentication](https://laravel.com/docs/authentication)

---

## ✅ Verification Checklist

- [ ] Composer installed and working
- [ ] Database created and configured
- [ ] Migrations run successfully
- [ ] Can access http://localhost:8000
- [ ] Can register new user
- [ ] Can find email verification token in logs
- [ ] Can verify email using token
- [ ] Can login with verified account
- [ ] Tests pass: `php artisan test`
- [ ] Can logout successfully

---

## 📞 Support

If you encounter issues:

1. Check the logs: `tail -f storage/logs/laravel.log`
2. Clear cache: `php artisan cache:clear`
3. Re-migrate: `php artisan migrate:fresh`
4. Run tests: `php artisan test`

---

**Status**: Day 1 ✅ Day 2 ✅ | Next: Day 3 🔄

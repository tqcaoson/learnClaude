# Community Forum - MVP

A Laravel-based community forum platform for knowledge sharing with authentication, posts, and comments management.

## Project Status: Day 1-4 Milestones (80% Complete)

✅ **Day 1 - Project Foundation** (70+ files):
- Project structure initialized
- Database migrations (users, posts, comments, login_attempts)
- Eloquent models with relationships
- DTOs for data transfer
- Repositories for database access
- Services for business logic
- Actions for use case orchestration
- Controllers (Auth, Posts, Comments)
- Policies for authorization
- Middleware (Auth, Rate Limiting)
- Form Requests with validation
- Blade templates (layouts, auth, posts, comments)
- Routes (web.php)
- Environment configuration

✅ **Day 2 - Authentication Features** (16 new tests):
- User registration with email verification
- Secure login with password hashing
- Email verification via tokens
- Rate limiting (5 attempts/15 min)
- Session management & remember me
- Custom exception handling
- Enhanced form validation
- Helper functions for templates
- Test seeder with sample users
- Comprehensive test coverage

✅ **Day 3 - Post Management CRUD** (20 new tests):
- Create posts with publish/draft status
- Edit posts with ownership validation
- Delete posts with soft deletes
- List posts with pagination
- Draft post visibility restrictions
- Enhanced post templates with better UX
- Status transition tests
- Post timestamp validation
- Comment integration ready

✅ **Day 4 - Comments & UI Polish** (Complete redesign):
- Comment system fully integrated
- Enhanced comment display template
- Improved CommentController with better messages
- Main layout redesign with modern styling
- Guest layout professional design
- Login/Register form improvements
- Smooth animations and transitions
- Auto-dismiss alerts
- Better mobile responsiveness
- Enhanced navigation with emoji icons
- User dropdown menu
- Improved form styling and validation feedback

## Prerequisites

- PHP 8.2+
- MySQL 5.7+ or PostgreSQL
- Composer
- Git

## Installation

1. **Clone the repository**
   ```bash
   cd /home/deck/Downloads/learnClaude/forum
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database in .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=forum_db
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

   Visit http://localhost:8000

## Features (MVP)

### Authentication ✅
- User registration with email
- Email verification
- Login/Logout with "Remember Me"
- Rate limiting (5 attempts/15 min)
- Session management

### Posts ✅
- Create posts (Members only)
- Read posts (Public)
- Edit/Delete own posts
- Draft/Published status
- Pagination support

### Comments ✅
- Add comments to posts (Members only)
- Delete own comments
- View all comments on post

### Authorization ✅
- Guest (read-only)
- Member (read + write)
- Ownership-based permissions

### Security ✅
- Password hashing (bcrypt)
- CSRF protection
- XSS protection (Blade escaping)
- SQL injection protection (Eloquent ORM)

## Project Structure

```
forum/
├── app/
│   ├── Actions/              # Use case orchestration
│   │   ├── Auth/
│   │   ├── Post/
│   │   └── Comment/
│   ├── DTOs/                 # Data transfer objects
│   ├── Http/
│   │   ├── Controllers/      # HTTP handlers
│   │   ├── Requests/         # Form validation
│   │   └── Middleware/       # Auth, rate limiting
│   ├── Models/               # Eloquent models
│   ├── Policies/             # Authorization logic
│   ├── Repositories/         # Database access
│   ├── Services/             # Business logic
│   └── Exceptions/           # Custom exceptions
├── config/                   # Configuration files
├── database/
│   └── migrations/           # Database schemas
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php              # Web routes
├── tests/
│   ├── Feature/             # Feature tests
│   └── Unit/                # Unit tests
├── .env.example             # Environment template
└── composer.json            # Dependencies
```

## API/Routes

### Authentication
- `GET /register` - Registration form
- `POST /register` - Register user
- `GET /login` - Login form
- `POST /login` - Login user
- `POST /logout` - Logout user
- `GET /email/verify-sent` - Verification email sent page
- `GET /email/verify/{token}` - Verify email

### Posts
- `GET /posts` - List published posts (paginated)
- `GET /posts/{id}` - Show post detail
- `GET /posts/create` - Create form (authenticated)
- `POST /posts` - Store post (authenticated)
- `GET /posts/{id}/edit` - Edit form (owner only)
- `PATCH /posts/{id}` - Update post (owner only)
- `DELETE /posts/{id}` - Delete post (owner only)

### Comments
- `POST /posts/{postId}/comments` - Add comment (authenticated)
- `DELETE /comments/{id}` - Delete comment (owner only)

## Testing

Run tests with:
```bash
php artisan test
```

Test files are in `tests/Feature` and `tests/Unit`

## Architecture Principles

1. **Thin Controllers**: Controllers only handle request/response
2. **Service Layer**: Business logic encapsulated in services
3. **Repository Pattern**: All DB access through repositories
4. **DTO Usage**: Clean data transfer between layers
5. **Policy-based Authorization**: Centralized permission logic
6. **Event-driven**: Ready for async job processing
7. **Soft Deletes**: Data preservation via soft delete

## Next Steps (Phase 2)

- [ ] User profiles + avatar upload
- [ ] Admin/Moderator dashboard
- [ ] Categories + Tags system
- [ ] Full-text search (Meilisearch)
- [ ] OAuth integration (Google, Facebook)
- [ ] Email notifications
- [ ] Reaction/voting system
- [ ] Advanced caching
- [ ] API endpoints (JSON)
- [ ] Performance optimization

## Database Schema

### users
- id, email (unique), password, name, email_verified_at, email_verification_token, timestamps

### posts
- id, user_id (FK), title, content, status (published/draft), deleted_at (soft delete), timestamps

### comments
- id, post_id (FK), user_id (FK), content, deleted_at (soft delete), timestamps

### login_attempts
- id, email, ip_address, attempts, locked_until, timestamps

## Development Notes

- **Email**: Currently logs to console (use `tail -f storage/logs/laravel.log`)
- **Database**: MySQL by default, can switch to PostgreSQL
- **Frontend**: Bootstrap 5 CDN for basic UI
- **Authentication**: Laravel session-based (Sanctum-ready for API)

## Troubleshooting

### Migrations not found
```bash
php artisan migrate --path=database/migrations
```

### Class not found
```bash
composer dump-autoload
```

### Permission denied (artisan)
```bash
chmod +x artisan
```

## License

MIT License - See LICENSE file

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Project SRS: ../docs/srs/SRS.md
- Architecture Guide: ../architect/laravel.architect.md

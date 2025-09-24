# Installation

## Requirements

See [Architecture Overview](./ARCHITECTURE.md) for complete technology stack details.

Essential requirements:
- PHP 8.3+
- MySQL 8.0+
- Node.js 18+
- Composer
- Git

## Setup Steps

### 1. Clone Repository
```bash
git clone [repository-url]
cd mesortgithub
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE mesort"

# Update .env with your database credentials
# DB_DATABASE=mesort
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate
```

### 5. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 6. Start Application
```bash
# Start PHP server
php artisan serve

# In another terminal, start Vite (for development)
npm run dev
```

Access the application at `http://localhost:8000`

## Optional Setup

### Create Admin User
```bash
php artisan user:create
```
Creates a new user account with admin privileges. Interactive command that prompts for email, password, and name.

### Install with Wizard
The `php artisan mesort:install` command performs these specific actions:
1. Runs database migrations (`migrate`)
2. Clears application cache (`optimize:clear`)
3. Creates required directories:
   - `public/images/preset_tokens/`
   - `public/images/classifiers/`
   - `storage/app/preset_tokens/`
   - `storage/app/classifiers/`
4. Prompts to create admin user with email/password

**Note**: This command exists but is untested and may not work properly. You might want to do these things manually.

### Laravel Telescope (Debug Tool) - optional
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

## Troubleshooting

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Connection Error
- Verify MySQL is running: `mysql -u root -p`
- Check `.env` database credentials
- Ensure database exists: `SHOW DATABASES;`

### Node/NPM Issues
- Clear npm cache: `npm cache clean --force`
- Delete node_modules: `rm -rf node_modules && npm install`

## Production Deployment

For production environments:

1. Set environment to production:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Optimize application:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. Configure web server (Nginx/Apache) to point to `public/` directory

4. Set up scheduled tasks in crontab:
   ```cron
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

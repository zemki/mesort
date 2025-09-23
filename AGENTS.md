# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MeSort is a research application for qualitative interview sorting tasks. Built with Laravel 10.x + Vue.js 2.7. Legacy "Study" naming internally, "Projects" in UI.

## Common Commands

```bash
npm run dev              # Start Vite dev server
npm run build            # Production build
php artisan test         # Run all tests
php artisan test --filter=TestName  # Run specific test
php artisan migrate      # Run migrations
./vendor/bin/pint        # Format PHP code
```

**Never use** `./vendor/bin/sail` - use standard commands directly

## Core Files & Architecture

### Key Controllers
- `app/Http/Controllers/StudyController.php` - Project management (legacy name)
- `app/Http/Controllers/InterviewController.php` - Sorting session management
- `app/Http/Controllers/TokenController.php` - Sortable item management

### Key Models
- `app/Models/Study.php` - Research projects (shows as "Projects" in UI)
- `app/Models/Token.php` - Sortable items
- `app/Models/Sorting.php` - Results storage

### Frontend Entry Points
- `resources/js/components.js` - Global Vue 2 component registration
- `resources/js/store/` - Vuex state management
- `resources/js/components/Interview/` - Interview components

### Custom Commands
- `app/Console/Commands/CreateUserCommand.php` - User creation
- `app/Console/Commands/DeleteUserCommand.php` - User deletion
- `app/Console/Commands/PruneInterviewPublicUrl.php` - URL cleanup

### Scheduled Tasks (Kernel.php)
- `telescope:prune --hours=336` - Daily at 23:00
- `pruneurls` - Daily at 23:30

## Code Style

- **PHP**: Laravel Pint configured in `pint.json`
- **Vue**: Vue 2.7 Composition API when possible
- **Components**: Global registration pattern in `components.js`
- **Database**: Eloquent models with relationships
- **Testing**: PHPUnit with SQLite in-memory

## Testing Instructions

```bash
# Run single test
php artisan test tests/Feature/StudyTest.php

# Run with coverage
php artisan test --coverage

# Database: Uses SQLite in-memory (no setup needed)
```

## Developer Environment

- PHP 8.3+ required
- Node.js for frontend build
- MySQL for production, SQLite for testing
- Copy `.env.example` to `.env`
- Run `php artisan key:generate`

## Warnings

- **Vue 2.7** not Vue 3 - different API
- **Study** terminology internally, **Projects** in UI
- Custom Tailwind breakpoints: `qsortxs`, `qsortsm`, `qsortmd`, `qsortlg`
- Uses Ziggy for Laravel routes in JavaScript
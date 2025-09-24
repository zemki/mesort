# MeSort Documentation

Research application for qualitative interview sorting tasks.

See [Installation Guide](./INSTALLATION.md) for setup instructions.

See [Architecture Overview](./ARCHITECTURE.md) for system design and structure.

## Commands

### Development
```bash
npm run dev              # Start Vite dev server
npm run build           # Production build
./vendor/bin/pint       # Format PHP code
```

### Custom Commands
```bash
php artisan user:create              # Interactive user creation with admin privileges
php artisan user:delete              # Delete user account by email/ID
php artisan pruneurls                # Remove expired public interview URLs
php artisan remove:check-unverified  # Clean up unverified user accounts
```

### Scheduled Tasks
Configured in `app/Console/Kernel.php`:
- `telescope:prune --hours=336` - Daily at 23:00
- `pruneurls` - Daily at 23:30


## Documentation

- [Installation Guide](./INSTALLATION.md) - Setup instructions
- [Architecture Overview](./ARCHITECTURE.md) - Tech stack and design




---

**Laravel**: 10.x | **PHP**: 8.3+ | **Vue**: 2.7

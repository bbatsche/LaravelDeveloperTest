# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Context

This is a Laravel 12.x developer test project demonstrating API data fetching, storage, and display. The project follows a Repository pattern with Data Transfer Objects (DTOs) using Spatie's Laravel Data package.

**API Source**: Uses JSONPlaceholder (https://jsonplaceholder.typicode.com/) for user profile data.

**Key Requirements**:
- Idempotent Laravel jobs to fetch and store API data
- Separation of data handling logic from execution mechanisms
- Artisan command for manual data import with visible output
- Scheduled tasks for automated data updates
- Repository pattern for data access layer
- Comprehensive tests for all services

## Development Environment

The project uses **DDEV** for local development with PHP 8.4, MariaDB 10.11, and nginx-fpm.

### Essential Commands

**Setup & Installation**:
```bash
ddev start                  # Start DDEV containers
ddev composer setup         # Full setup: install deps, generate key, run migrations, build assets
```

**Development**:
```bash
ddev composer dev           # Start all development services (server, queue, logs, vite)
                           # Runs: artisan serve + queue:listen + pail + npm dev
```

**Testing**:
```bash
ddev composer test          # Clear config cache and run full test suite
ddev php artisan test --filter=ProfileRepository  # Run specific test class
```

**Code Quality**:
```bash
ddev composer pint       # Run Laravel Pint code formatter
```

**Data Import** (when implemented):
```bash
php artisan profiles:fetch  # Manual data import command (to be created)
```

## Architecture

### Data Layer Architecture

The project uses a three-tier data architecture:

1. **Models** (`app/Models/`): Eloquent models with relationships
   - `Profile`: Main entity with `hasOne` relationships to Address and Company
   - `Address`: Profile's physical address
   - `Company`: Profile's company information
   - Uses `$fillable` arrays and eager loading via `$with`

2. **Data Transfer Objects** (`app/Data/`): Type-safe DTOs using Spatie Laravel Data
   - `ProfileData`, `AddressData`, `CompanyData`
   - Uses PHP 8.4 property hooks (`private(set)`) for immutability
   - Includes validation attributes (`#[Email]`, `#[Digits(10)]`, etc.)
   - Computed properties for presentation (e.g., `$formattedPhone`, `$telHref`)
   - Static `fromMultiple()` factory methods for complex instantiation

3. **Repositories** (`app/Repository/`): Data access abstraction
   - `ProfileRepository`: CRUD operations returning `ProfileData` DTOs
   - Methods: `find()`, `all()`, `exists()`, `create()`
   - Handles model-to-DTO conversion and relationship creation

### Job Architecture

Jobs in `app/Jobs/` must implement:
- `ShouldQueue`: Make jobs queueable
- `ShouldBeUnique`: Ensure idempotency (prevents duplicate execution)
- Use `Queueable` trait for queue configuration

Example: `FetchProfiles` job is currently a skeleton awaiting implementation.

### Testing Structure

Tests are organized by feature area:
- `tests/Feature/Http/`: Controller and route tests
- `tests/Feature/Jobs/`: Job execution tests
- `tests/Feature/Models/`: Model relationship and behavior tests
- All tests should be Feature tests unless testing isolated units

## Important Patterns

### Data Object Pattern

When creating or modifying Data objects:
- Use property hooks `public private(set) Type $property` for immutability
- Include Spatie validation attributes on properties
- Use `Optional` type for nullable/optional fields
- Create `#[Computed]` properties for derived data
- Implement static factory methods when constructor is private

### Repository Pattern

Repositories must:
- Accept the model via constructor injection
- Return Data objects, not Eloquent models
- Use `ProfileData::from()` for single conversions
- Use `ProfileData::collect()` for collections
- Handle related model creation within repository methods

### Job Idempotency

Jobs fetching external data should:
- Check `exists()` before creating records
- Use database transactions for multi-record operations
- Implement `ShouldBeUnique` interface
- Use `profile_id` (external API ID) for uniqueness checks, not database `id`

## Database Schema

- **profiles**: Main table with `profile_id` (external API ID), name, username, email, phone, extension, website
- **addresses**: Foreign key to profiles, includes street, suite, city, zipcode, geo coordinates
- **companies**: Foreign key to profiles, includes name, catchPhrase, bs

All tables use `profile_id` as the foreign key column name pointing to `profiles.id`.

## Dependencies

Key packages:
- **spatie/laravel-data**: DTO implementation with validation
- **laravel/pail**: Real-time log viewer
- **laravel/pint**: Code formatter (Laravel-flavored PHP-CS-Fixer)
- **Tailwind CSS 4.0**: Frontend styling via Vite

## Notes for Implementation

When implementing the profile fetching feature:

1. **Service Layer**: Create a service class in `app/Services/` to handle API communication
2. **Job Implementation**: `FetchProfiles` should dispatch to service, then use repository to persist
3. **Artisan Command**: Create in `app/Console/Commands/` to manually trigger job with output
4. **Scheduling**: Add to `routes/console.php` using `->schedule()`
5. **Controller**: Implement `ProfileController::index()` to display profiles using repository
6. **Routes**: Add profile routes to `routes/web.php`
7. **Views**: Create Blade views in `resources/views/` to display profile data
8. **Tests**: Write feature tests for service, repository, job, and controller

Follow the existing patterns: immutable DTOs, repository abstraction, and comprehensive type safety.

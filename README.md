# Laravel Admin Panel (Laravel 12)

> A comprehensive admin panel built with **Laravel 12**, featuring user management, profile management, settings, and a modern dashboard with sidebar navigation, contact management, emailtemplate management, log activity, campaign management, send bulk email using queue.


## Requirements

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL >= 8.0
- Laravel 12.x

## Installation

### 1. Clone the Repository

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
copy .env.example .env
```

Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Database

Create a MySQL database named `laravel12_db`:

```sql
CREATE DATABASE laravel12_db;
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Seed Database

Seed the database with an admin user and test users:

```bash
php artisan db:seed --class=AdminUserSeeder
```

This will create:
- **Admin User**: `admin@example.com` / `password`
- **Test User 1**: `john@example.com` / `password`
- **Test User 2**: `jane@example.com` / `password`

### 9. Build Frontend Assets

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

### 10. Start Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Usage

### Accessing the Admin Panel

1. Navigate to `http://localhost:8000`
2. You'll be redirected to the login page
3. Login with admin credentials:
   - **Email**: `admin@example.com`
   - **Password**: `password`

### Available Routes

- `/` - Redirects to login
- `/login` - Login page
- `/register` - Admin registration
- `/dashboard` - Admin dashboard

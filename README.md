<div class="filament-hidden">

![FilaKit](https://raw.githubusercontent.com/jeffersongoncalves/filakitv5/main/art/jeffersongoncalves-filakitv5.png)

</div>

# FilaKit Start Kit Filament 5.x and Laravel 13.x

## About FilaKit

FilaKit is a robust starter kit built on Laravel 13.x and Filament 5.x, designed to accelerate the development of modern
web applications with a ready-to-use multi-panel structure.

## Features

- **Laravel 13.x** - The latest version of the most elegant PHP framework
- **Filament 5.x** - Powerful and flexible admin framework
- **Multi-Panel Structure** - Includes three pre-configured panels:
    - Admin Panel (`/admin`) - For system administrators
    - App Panel (`/app`) - For authenticated application users
    - Public Panel (frontend interface) - For visitors
- **Environment Configuration** - Centralized configuration through the `config/filakit.php` file

## System Requirements

- PHP 8.3 or higher
- Composer
- Node.js and PNPM

## Installation

Clone the repository
``` bash
laravel new my-app --using=jeffersongoncalves/filakitv5 --database=mysql
```

### Using FilaKit CLI

Or use [FilaKit CLI](https://github.com/jeffersongoncalves/filakit-cli) for a simplified setup:

```bash
filakit new my-app --kit=jeffersongoncalves/filakitv5
```

> Install FilaKit CLI: `composer global require jeffersongoncalves/filakit-cli`

###  Easy Installation

FilaKit can be easily installed using the following command:

```bash
php install.php
```

This command automates the installation process by:
- Installing Composer dependencies
- Setting up the environment file
- Generating application key
- Setting up the database
- Running migrations
- Installing Node.js dependencies
- Building assets
- Configuring Herd (if used)

### Manual Installation

Install JavaScript dependencies
``` bash
pnpm install
```
Install Composer dependencies
``` bash
composer install
```
Set up environment
``` bash
cp .env.example .env
php artisan key:generate
```

Configure your database in the .env file

Run migrations
``` bash
php artisan migrate
```
Build frontend assets
``` bash
pnpm run build
```
Run the server
``` bash
php artisan serve
```

## Installation with Docker

Clone the repository
```bash
laravel new my-app --using=jeffersongoncalves/filakitv5 --database=mysql
```

Move into the project directory
```bash
cd my-app
```

Install Composer dependencies
```bash
composer install
```

Set up environment
```bash
cp .env.example .env
```

Configuring custom ports may be necessary if you have other services running on the same ports.

```bash
# Application Port (ex: 8080)
APP_PORT=8080

# MySQL Port (ex: 3306)
FORWARD_DB_PORT=3306

# Redis Port (ex: 6379)
FORWARD_REDIS_PORT=6379

# Mailpit Port (ex: 1025)
FORWARD_MAILPIT_PORT=1025
```

Start the Sail containers
```bash
./vendor/bin/sail up -d
```
You won’t need to run `php artisan serve`, as Laravel Sail automatically handles the development server within the container.

Attach to the application container
```bash
./vendor/bin/sail shell
```

Generate the application key
```bash
php artisan key:generate
```

Install JavaScript dependencies
```bash
pnpm install
```

## Authentication Structure

FilaKit comes pre-configured with a custom authentication system that supports different types of users:

- `Admin` - For administrative panel access
- `User` - For application panel access

## Development

``` bash
# Run the development server with logs, queues and asset compilation
composer dev

# Or run each component separately
php artisan serve
php artisan queue:listen --tries=1
pnpm run dev
```

## Customization

### Panel Configuration

Panels can be customized through their respective providers:

- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Providers/Filament/AppPanelProvider.php`
- `app/Providers/Filament/PublicPanelProvider.php`

Alternatively, these settings are also consolidated in the `config/filakit.php` file for easier management.

### Themes and Colors

Each panel can have its own color scheme, which can be easily modified in the corresponding Provider files or in the
`filakit.php` configuration file.

### Configuration File

The `config/filakit.php` file centralizes the configuration of the starter kit, including:

- Panel routes
- Middleware for each panel
- Branding options (logo, colors)
- Authentication guards

## User Profile — joaopaulolndev/filament-edit-profile

This project already comes with the Filament Edit Profile plugin integrated for the Admin and App panels. It adds a complete profile editing page with avatar, language, theme color, security (tokens, MFA), browser sessions, and email/password change.

- Routes (defaults in this project):
  - Admin: /admin/my-profile
  - App: /app/my-profile
- Navigation: by default, the page does not appear in the menu (shouldRegisterNavigation(false)). If you want to show it in the sidebar menu, change it to true in the panel provider.

Where to configure
- Panel providers
  - Admin: app/Providers/Filament/AdminPanelProvider.php
  - App: app/Providers/Filament/AppPanelProvider.php
  In these files you can adjust:
  - ->slug('my-profile') to change the URL (e.g., 'profile')
  - ->setTitle('My Profile') and ->setNavigationLabel('My Profile')
  - ->setNavigationGroup('Group Profile'), ->setIcon('heroicon-o-user'), ->setSort(10)
  - ->shouldRegisterNavigation(true|false) to show/hide it in the menu
  - Shown forms: ->shouldShowEmailForm(), ->shouldShowLocaleForm([...]), ->shouldShowThemeColorForm(), ->shouldShowSanctumTokens(), ->shouldShowMultiFactorAuthentication(), ->shouldShowBrowserSessionsForm(), ->shouldShowAvatarForm()

- General settings: config/filament-edit-profile.php
  - locales: language options available on the profile page
  - locale_column: column used in your model for language/locale (default: locale)
  - theme_color_column: column for theme color (default: theme_color)
  - avatar_column: avatar column (default: avatar_url)
  - disk: storage disk used for the avatar (default: public)
  - visibility: file visibility (default: public)

Migrations and models
- The required columns are already included in this kit’s default migrations (users and admins): avatar_url, locale and theme_color, using the names defined in config/filament-edit-profile.php.
- The App\Models\User and App\Models\Admin models already read the avatar using the plugin configuration (getFilamentAvatarUrl).

Avatar storage
- Make sure the filesystem disk is configured and that the storage link exists:
  php artisan storage:link
- Adjust the disk and visibility in the config file according to your infrastructure.

Quick access
- Via direct URL: /admin/my-profile or /app/my-profile
- To make it visible in the sidebar navigation, set shouldRegisterNavigation(true) in the respective Provider.

Reference
- Plugin repository: https://github.com/joaopaulolndev/filament-edit-profile

## Impersonation — stechstudio/filament-impersonate

This project ships with `stechstudio/filament-impersonate` wired so administrators can impersonate application users from the Admin panel only.

How it is wired in this kit
- Admin → User direction only. The `App\Models\Admin` model returns `canImpersonate(): true` and `App\Models\User` returns `canImpersonate(): false`, so only admins can trigger impersonation.
- Action available on the Admin `UserResource`:
  - Table row action in `app/Filament/Admin/Resources/Users/Tables/UsersTable.php`
  - Header action on `app/Filament/Admin/Resources/Users/Pages/EditUser.php` and `ViewUser.php`
- Every action is configured with `->guard('web')->redirectTo('/app')` so the impersonated user is logged into the App panel under the `web` guard.
- Global action defaults (color, icon-only button) are applied once in `app/Providers/AppServiceProvider.php` via `Impersonate::configureUsing(...)`.

Restricting who can be impersonated
- Add a `canBeImpersonated(): bool` method on `App\Models\User` if you need to exclude specific records (e.g. block staff accounts). When the method is absent, every user is impersonable.

Leaving impersonation
- A banner is automatically rendered on Filament pages while impersonating. Click "Leave impersonation" to return to the Admin session.
- For non-Filament Blade layouts, add `<x-impersonate::banner/>` right before `</body>` so the banner stays visible outside Filament.

Optional ENV configuration
- `FILAMENT_IMPERSONATE_REDIRECT` — global fallback redirect (this kit overrides per action with `/app`).
- `FILAMENT_IMPERSONATE_ALLOW_SOFT_DELETED` — set to `true` to allow impersonating soft-deleted users.
- `FILAMENT_IMPERSONATE_BANNER_STYLE` — `dark` (default), `light`, or `auto`.
- `FILAMENT_IMPERSONATE_BANNER_POSITION` — `top` (default) or `bottom`.

Reference
- Plugin repository: https://github.com/stechstudio/filament-impersonate

## Developer Logins — dutchcodingcompany/filament-developer-logins

Quick-login buttons are enabled on the Admin and App panels for local development. The list is built dynamically from the database, restricted to active records (`status = true`).

- Admin panel (`app/Providers/Filament/AdminPanelProvider.php`): pulls from `App\Models\Admin`.
- App panel (`app/Providers/Filament/AppPanelProvider.php`): pulls from `App\Models\User`.
- Both panels are gated by `app()->environment('local')`, so the buttons never appear in staging/production.

To change the column shown on the buttons, edit the `->users(...)` closure in each provider (for example, swap `pluck('email', 'name')` for `pluck('email', 'email')`).

Reference
- Plugin repository: https://github.com/DutchCodingCompany/filament-developer-logins

## Resources

FilaKit includes support for:

- User and admin management
- Multi-guard authentication system
- Tailwind CSS integration
- Database queue configuration
- Customizable panel routing and branding

## License

This project is licensed under the [MIT License](LICENSE).

## Credits

Developed by [Jefferson Gonçalves](https://github.com/jeffersongoncalves).

# Zii B2C Platform

## Overview
Zii B2C is a modern, full-stack web application built for scalable and high-performance business-to-consumer and business-to-business operations. At its core, the project leverages a robust Laravel backend integrated seamlessly with a responsive React frontend through Inertia.js, giving the feel of a Single Page Application (SPA) while maintaining powerful server-side tooling.

## 🚀 Tech Stack

### Backend
- **Framework:** Laravel 13 (PHP 8.3+)
- **Architecture:** Repository Pattern (`prettus/l5-repository`), DTOs (`spatie/laravel-data`)
- **Authentication:** Laravel Fortify
- **Authorization & Roles:** Spatie Permission Tracker (`spatie/laravel-permission`)
- **Multi-Tenancy:** Stancl Tenancy (`stancl/tenancy`)
- **Media Management:** Spatie Media Library (`spatie/laravel-medialibrary`)
- **Localization:** Laravel Localization (`mcamara/laravel-localization`)
- **Database:** PostgreSQL

### Frontend
- **Framework:** React 19
- **Bridge:** Inertia.js v3.0
- **Language:** TypeScript 5.7+
- **Styling:** Tailwind CSS 4 & class-variance-authority (CVA)
- **UI Components:** Shadcn UI (Radix UI base)
- **Data Tables:** TanStack Table v8 (with server-side filtering, sorting, pagination, and `dnd-kit` for column reordering)
- **Validation:** Zod
- **I18n:** `i18next` & `react-i18next`
- **Build Tool:** Vite 8

## ✨ Key Features

- **Multi-tenant Architecture:** Completely isolated workspaces for different tenants/organizations.
- **Advanced Dynamic Datatables:** Highly reusable, generic data tables with customizable columns, drag-and-drop ordering, and server-side operations.
- **Robust ACL:** Granular role and permission management.
- **Media & File Handling:** Seamless attachments, automatic conversions, and image manipulation.
- **Global Reach:** Deep integration of internalization (i18n) for both Server and Client sides.
- **Developer Experience (DX):**
  - Fully typed API integrations and routing using `@laravel/vite-plugin-wayfinder`.
  - Type-safe React components and rigorous ESLint configuration.
  - Automatic formatting with Prettier & PHP-CS-Fixer.

## 🛠️ Getting Started

### Prerequisites
- [PHP 8.3+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Node.js 22+](https://nodejs.org/)
- [Yarn](https://yarnpkg.com/) (v4.14+)
- PostgreSQL

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd ziib2c
   ```

2. **Run the automated setup script:**
   The project includes a convenient post-creation script. You can run:
   ```bash
   composer run setup
   ```
   *Alternatively, perform the steps manually:*
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   yarn install
   yarn build
   ```

### Development
To spin up all development services simultaneously (PHP Server, Queue, Pail Logs, and Vite), run:
```bash
composer run dev
# OR manually:
# php artisan serve
# yarn dev
```

## 📂 Project Structure

```text
ziib2c/
├── app/                  # Laravel Backend: Controllers, Models, Repositories, DTOs
├── bootstrap/            # Application bootstrapping
├── config/               # Application configuration
├── database/             # Migrations, Factories, and Seeders
├── public/               # Publicly accessible files & compiled assets
├── resources/            # React Codebase & Views
│   ├── js/
│   │   ├── components/   # Reusable UI components (Shadcn, AppSidebar)
│   │   ├── hooks/        # Custom React hooks (e.g., useDataTable)
│   │   ├── pages/        # Inertia page views routing
│   │   ├── lib/          # Utilities
│   │   └── types/        # TypeScript declarations
│   └── css/              # Tailwind entry points
├── routes/               # Laravel Web/API routing
└── tests/                # Pest PHP feature and unit tests
```

## 🧪 Testing and Linting

We maintain strict code quality standards to ensure consistency and reliability.

**PHP & Backend:**
- Run tests: `composer run test` (via Pest PHP)
- Check styling: `composer run lint:check` (via Laravel Pint)
- Fix styling: `composer run lint`

**TypeScript & Frontend:**
- Lint code: `yarn lint`
- Type check: `yarn types:check`
- Format code: `yarn format:check`
- Fix formatting: `yarn lint:fix`

To run all Continuous Integration checks locally before committing:
```bash
composer run ci:check
```

## 📝 License

This project is licensed under the [MIT License](LICENSE).

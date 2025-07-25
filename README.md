# Phuelone Framework

Phuelone is a lightweight PHP framework designed for simplicity, scalability, and enterprise-grade applications. Inspired by Laravel, it provides a clean and intuitive development experience.

## Features
- **MVC Architecture**: Clean separation of concerns.
- **Routing System**: Flexible and secure routing.
- **Middleware**: Built-in support for CSRF protection, rate limiting, and RBAC.
- **Dynamic Database Switching**: Support for MySQL, PostgreSQL, and SQLite.
- **Templating Engine**: Advanced features like caching, inheritance, directives, filters, debugging, i18n, and partials.
- **Scalability**: Redis caching and RabbitMQ integration.
- **Security**: Encryption, audit logging, and role-based access control.
- **CI/CD Pipeline**: GitHub Actions for testing, linting, and deployment.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/JadeHyphen/phuelone.git
   ```

2. Navigate to the project directory:
   ```bash
   cd phuelone
   ```

3. Install dependencies:
   ```bash
   composer install
   ```

4. Configure your environment:
   - Copy `.env.example` to `.env`.
   - Update database credentials and other settings in `.env`.

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

### Routing
Define routes in `routes/web.php`. Example:
```php
use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);
```

### Middleware
Add middleware in `app/Http/Middleware`. Example:
```php
$router->middleware('csrf', CsrfMiddleware::class);
```

### Database
Configure database settings in `.env`. Example:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phuelone
DB_USERNAME=root
DB_PASSWORD=
```

### Templating
Use the built-in templating engine for views. Example:
```php
return view('home', ['title' => 'Welcome to Phuelone']);
```

## Contributing

1. Fork the repository.
2. Create a new branch:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add feature-name"
   ```
4. Push to your branch:
   ```bash
   git push origin feature-name
   ```
5. Open a pull request.

## License

Phuelone is open-source and licensed under the MIT License.

# LaradockAPI - Laravel 11 API with Docker

LaradockAPI is an API built with Laravel 11, optimized for deployment in a Docker environment. This project includes a Docker container configuration for PHP, Nginx, MySQL, Redis, and PhpMyAdmin. Automated test scripts (linter, PHPStan, unit tests) are also integrated to ensure code quality and stability.

## Prerequisites

- **Docker** and **Docker Compose** must be installed on your machine.

## Installation

1. Clone this repository to your local machine:

   ```bash
   git clone https://github.com/StephaneKuma/laradockapi.git
   cd laradockapi

2. Copy the .env.example file to .env and configure environment variables if necessary:

    ```bash
    cp .env.example .env

3. Start the containers with Docker Compose:

    ```bash
    docker compose up -d --build

4. Install Laravel dependencies inside the PHP container:

    ```bash
    docker compose exec app composer install

5. Generate the application key:

    ```bash
    docker compose exec app php artisan key:generate

6. Run migrations to create the database tables:

    ```bash
    docker compose exec app php artisan migrate


## Service Access

- Laravel API: [http://api.localhost](http://api.localhost/)
- PhpMyAdmin: [http://pma.localhost](http://pma.localhost/) (MySQL database access)
- Mailhog: [http://mail.localhost](http://mail.localhost/) (for mail testing, without the port)

## Useful Commands

- Composer:

    ```bash
    docker compose exec app composer <command>

- Run linter:

    ```bash
    docker compose exec app composer lint

- Run fix:

    ```bash
    docker compose exec app composer fix

- Run static analysis:

    ```bash
    docker compose exec app composer stan

- Run tests:

    ```bash
    docker compose exec app composer pest

- Run linter, fixer, stan, tests:

    ```bash
    docker compose exec app composer clean

- Run ide helper:

    ```bash
    docker compose exec app composer ide-helper

- Run generate API docs:

    ```bash
    docker compose exec app composer docs

## Code Quality and Testing

- Linter: PHP CodeSniffer is configured to enforce PSR-12 standards.
- PHPStan: Static analysis is used to detect code errors.
- Unit Tests: PHPUnit is used for unit testing the application.

## Contribution

Contributions are welcome! Please follow best practices and run tests before submitting a pull request.

## License

This project is licensed under the MIT License.

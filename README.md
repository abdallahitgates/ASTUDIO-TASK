<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Setup

Requirements:

PHP 8.x
Laravel 10.x
MySQL or PostgreSQL
Composer
Node.js & NPM (for frontend dependencies, if applicable)
Postman (for testing API requests)

## Installation Steps

Clone the Repository:

git clone https://github.com/abdallahitgates/ASTUDIO-TASK.git
cd job-board

Install Dependencies:

composer install
npm install

Configure Environment:

Copy the .env.example file and rename it to .env

cp .env.example .env

Update database credentials in .env :

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

Generate Application Key:

php artisan key:generate

Run Migrations & Seeders:

php artisan migrate --seed

Start the Application:

php artisan serve

## API Documentation

Base URL:

http://127.0.0.1:8000/api

Filtering Syntax

Filters can be applied using query parameters. Example:

GET /api/jobs?filter=salary_min>=50000 AND is_remote=true AND job_type IN(Full-time,Contract)

Supported Filter Operations:

1- Text Fields (title, description, company_name)

Equality: = , !=

Contains: ~ (LIKE)

Example:

GET /api/jobs?filter=title~"Developer"

2- Number Fields (salary_min, salary_max)

Equality: =, !=

Comparison: >, <, >=, <=

Example:

GET /api/jobs?filter=salary_min>=60000

3- Boolean Fields (is_remote)

Equality: =, !=

Example:

GET /api/jobs?filter=is_remote=true

Select Fields (job_type, status)

Equality: =, !=

4- Multiple values: IN(value1,value2,...)

Example:

GET /api/jobs?filter=job_type IN(Freelance,Part-time)

5- Date Fields (published_at, created_at)

Equality: =, !=

Comparison: >, <, >=, <=

Example:

GET /api/jobs?filter=published_at>=2024-01-01

6- Relational Fields (languages, locations, categories)

Equality: =

Exists: EXISTS

Example:

GET /api/jobs?filter=languages="English"

7- Custom Attributes (attribute:name)

Equality: =, !=

Comparison: >, <, >=, <=

Contains: LIKE

8- Multiple values: IN(value1,value2,...)

Example:

GET /api/jobs?filter=attribute:experience>=3

## Testing with Postman

1- Import Postman Collection

2- Open Postman

4- Click Import

5-Select the collection from docs/Job-Board.postman_collection.json

Use Predefined Requests

 * Select the appropriate request (e.g., GET /api/jobs)

Modify query parameters as needed

Click Send

## Notes on Design Decisions

Query Optimization: The service avoids N+1 queries by using with() for relationships.

Extensibility: Filters are modular, making it easy to add new conditions.

Validation & Error Handling: Invalid filters return JSON errors with meaningful messages.

Pagination: The API returns paginated results (20 per page).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

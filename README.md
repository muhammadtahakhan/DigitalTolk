## Translation Management Service
A Laravel-based service for managing and exporting translations with a strong focus on clean architecture, performance, and scalability.
### Requirements
PHP 8.1 or higher, Composer, MySQL 8.0+

### 1. Clone the Repository
git clone https://github.com/muhammadtahakhan/DigitalTolk.git
cd translation-management-service
### 2. Install Dependencies
composer install

### 3. Environment  setup
```
cp .env.example .env
php artisan key:generate
```
### Configure api token in .env:
API_TOKEN={any string will to use as api key or brear token}
### Configure your database in .env:
```
DB_HOST=127.0.0.1
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={database name}
DB_USERNAME={db user name}
DB_PASSWORD={your_password}
```

### 4. Run Migrations
php artisan migrate
### 5. Seed the Database
php artisan db:seed
### insert dummy data in table
php artisan translations:seed 100000

<!-- -------------------------------------------------- -->
## Architecture & Design Choices
The architecture uses a layered Service Pattern to keep different parts of the code separate and easier to maintain, test, and read. When HTTP requests come in, they flow from controllers to services to models. This keeps controllers lightweight while keeping the business logic where it belongs—in the services. The service interface acts as a repository abstraction, which makes it simple to swap out implementations and write unit tests.
##
For performance, we've added database indexing to speed up reads and help the system scale. We're also caching the export endpoint specifically, using locale + tags as the cache key. The cache automatically clears itself whenever there's a create, update, or delete operation, so the data stays consistent.


### Request Flow:
Request -> Controller -> Service -> Model

Request: Input validation (also can authorization).\n
Controllers: Handle HTTP requests/responses.
Services: Contain business logic and data manipulation.
Models: Represent database entities.

### Repository Pattern via Service Interface
Why? Allows easy swapping of implementations and better testability.


### Performance Optimizations
#### Db optimization:
implemented indexing to improve read performance and scalability.

### Caching Strategy
Export endpoint: Cached for using locale+tags as cache key,
Cache removed: Cleared on create/update/delete operations

## Code Standards
PSR-12: PHP coding standard and SOLID principles: Applied throughout

## Api Doc
file 
Digitaltolk.postman_collection is included

## I implemented basic functionality; additional performance optimizations, Docker Compose, and testing, queue, redis can be added if needed. 

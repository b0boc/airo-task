### Backend:
-  PHP@8.4.3
-  MongoDB@latest

Depends on MongoDB server, which has to be enabled on port 27017. And the mongodb php extension. 

In .env file the db driver can be switched to a different one if needed. DB_HOST should be set to 127.0.0.1 instead of mongodb.

## Usage
```
composer install
php artisan serve
```

http://localhost:8000

## Features
- register users, login and logut
- handle JWT generation / invalidation 
- protect routes with auth middleware
- error handling
- unit testing


### Repo history
```
composer require mongodb/laravel-mongodb

composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```
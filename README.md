# Laravel-BlogApp
Clone of qgisk/BlogApp but made with Laravel


Clone
composer install
npm i

cp .env-example .env

Fill in database info (mysql) in env

php artisan key:generate
php artisan jwt:secret

php artisan migrate:fresh --seed

For development
php artisan serve & npm run watch (Concurrently)

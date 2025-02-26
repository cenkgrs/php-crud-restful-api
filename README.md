Installation without Docker

- git clone the project

- cd php-crud-restful-api

-composer install

- cp .env.example .env

- Open .env file and set DB_DATABASE, DB_USERNAME, DB_PASSWORD fields

- php artisan key:generate
- php artisan migrate
- php artisan db:seed

- php artisan serve

You can now test the api via Postman or with other alternatives you choose

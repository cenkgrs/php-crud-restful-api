Installation with Docker

- git clone the project

- cd php-crud-restful-api

- docker compose up -d db

- docker compose build

- docker compose up laravelapp

  Then on another terminal run these artisan commands

- docker compose exec laravelapp php artisan key:generate
- docker compose exec laravelapp php artisan migrate
- docker compose exec laravelapp php artisan db:seed

  Then import postman collection to send requests to api

  Before sending other request first login request with seeded user data
  which will be on the postman login request params

  Then set Token -> Bearer token, token will be returned as response from login request

  Now you can try other api requests with this token 

Installation without Docker

- git clone the project

- cd php-crud-restful-api

- composer install

- cp .env.example .env

- Open .env file and set DB_DATABASE, DB_USERNAME, DB_PASSWORD fields

- php artisan key:generate
- php artisan migrate
- php artisan db:seed

- php artisan serve

You can now test the api via Postman or with other alternatives you choose

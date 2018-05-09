# aws-account-manager

http://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles_providers_enable-console-custom-url.html



# how to create dev env


1. Clone this repository.
2. Copy to create `.env` file.  `cp .env.example .env`.
3. `docker-compose up -d`
4. `docker-compose exec php php artisan key:generate`
5. `docker-compose exec php php artisan migrate`
6. `docker-compose exec php php artisan db:seed`


You will be able to login with test account `admin@example.com`:`password`.

http://localhost

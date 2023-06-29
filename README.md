# SWC


### Configure the application

Create the database:

```
mysql -uroot -p
CREATE DATABASE swc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
quit
```

Copy and edit the `.env` file and enter your database details:

```
cp .env.example .env
```

Install the project dependencies and start the PHP server:

```
composer install

php artisan serve
```

Migrate tables with users seeders:

```
php artisan migrate --seed
```
Or refresh tables:

```
php artisan migrate:refresh --seed
```

Loading [127.0.0.1:8000](127.0.0.1:8000).

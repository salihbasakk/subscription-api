# Subscription Management API

Mobile apps subscription and in-app purchases backend API.

## Database Design

![](db-schema.png)

## Installation

If you need to install php and composer, use any of the official [PHP](https://www.php.net/downloads.php) and
[Composer](https://getcomposer.org/download/) installers provided for your operating system.

Clone the project repository:

```bash
git clone git@github.com:salihbasakk/subscription-api.git
```
Create .env file from .env.dist (with related database configuration)

Example:

```bash
DATABASE_NAME=subscription-api
DATABASE_ROOT_PASSWORD=123456!
DATABASE_PORT=3306
```

```bash
docker-compose up -d --build
```

```bash
docker exec -it php-app /bin/sh
```

```bash
php bin/console doctrine:migrations:migrate
```

```bash
php bin/console doctrine:fixtures:load
```

## Test

```bash
php vendor/bin/phpunit
```

You can either use postman collection subscriptionApi.postman_collection.json / '$BASE_URL/api/doc' for request





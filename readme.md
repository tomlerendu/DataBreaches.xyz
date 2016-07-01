# DataBreaches.xyz

[DataBreaches.xyz](https://databreaches.xyz) is a website that categorieses how well an organisation handles your personal information. Each organisation is assigned a score based on the number of data breaches they have had and how well they have reacted to them.

This project is under the MIT licence. The website code is available for reuse and modification without restriction.


## Setup

### Requirements

* PHP 7+
* MySQL

### Install

1. Clone repo `git clone https://github.com/tomlerendu/DataBreaches.xyz`
2. Download PHP dependencies `composer update`
3. Download front-end dependencies `npm update`
4. Create `.env` file in the root directory. Example:
```
   APP_ENV=local
   APP_NAME=DataBreaches.xyz
   APP_EMAIL=databreaches@localhost
   APP_DEBUG=true
   APP_KEY=abcdefghijklmnopqrstuvwxyz
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=8889
   DB_DATABASE=
   DB_USERNAME=
   DB_PASSWORD=

   CACHE_DRIVER=file
   SESSION_DRIVER=file
   QUEUE_DRIVER=sync

   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379

   MAIL_DRIVER=smtp
   MAIL_HOST=mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
```
5. Run database migrations `php artisan migrate`
6. Run GULP `gulp`
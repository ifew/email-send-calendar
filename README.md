# Sample to using PHPMailer send iCal for automatic create event in calendar via email

## Setup

### Install package via composer

```bash
composer install
```

### PHP 7.3 and Nginx

Based from [romeoz/docker-phpfpm:7.3](https://github.com/romeOz/docker-nginx-php/tree/master/7.3)

### Build Image

```bash
docker build -t nginx_php:7.3 .
```

### Build Container

```bash
docker run -d -i -p 80:80 -v ${PWD}:/var/www/app/ --net db_network --name mail-invite-meeting nginx_php:7.3
```

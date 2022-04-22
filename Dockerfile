FROM php:7.0-apache
COPY chat_app/ /var/www/html
EXPOSE 80
# Użyj oficjalnego obrazu PHP z Apache
FROM php:8.2-apache

# Skopiuj pliki PHP do katalogu serwera
COPY ./app /var/www/html/

# Ustaw odpowiednie uprawnienia
RUN chown -R www-data:www-data /var/www/html

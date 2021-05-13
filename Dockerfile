FROM wordpress:5-php7.4-apache

# Set up Apache port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
## Delete all pre-installed plugins
RUN cd /usr/src/wordpress/wp-content && rm -rfv plugins themes

# Install composer dependencies
RUN apt-get update && apt-get install -y unzip
COPY --from=composer /usr/bin/composer /usr/bin/composer

### Wordpress Customization ###
WORKDIR /tmp
COPY composer.* ./
RUN composer install
RUN mv vendor /usr/src/wordpress

COPY wordpress/wp-content/plugins wp-content/plugins/
COPY wordpress/wp-content/themes wp-content/themes/
RUN cp -R wp-content /usr/src/wordpress
RUN ls wp-content/themes/ && ls wp-content/plugins/

# Copy custom packages to src code
#COPY packages /usr/src/wordpress
#RUN cd /usr/src/wordpress/donation && composer install
#COPY payment_config.php /usr/config/

# Required for the base image's entrypoint
WORKDIR /var/www/html

# Copy custom configuration file into the public html folder
COPY wordpress/wp-config.php .
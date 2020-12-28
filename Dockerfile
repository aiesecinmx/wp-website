FROM wordpress:5-php7.4-apache

# Set up base image
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN cd /usr/src/wordpress/wp-content && rm -rfv plugins themes
RUN apt-get update && apt-get install -y unzip
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install composer dependencies
WORKDIR /tmp
COPY composer.* ./
RUN composer install

# Move vendor dependencies into Wordpress' source folder
RUN mv vendor /usr/src/wordpress

### Wordpress Customization ###

# Copy custom plugins into the tmp folder
COPY wordpress/wp-content/plugins/ wp-content/plugins/

# Replace wp-contents into Wordpress' source
RUN cp -R --parents wp-content/* /usr/src/wordpress/

# Needed for Wordpress' entrypoint
WORKDIR /var/www/html

# Copy custom configuration file into the public html folder
COPY wordpress/wp-config.php .

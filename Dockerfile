FROM wordpress:5.5-php7.4-apache

# Install Wordpress plugins with Composer into a temporary folder
RUN apt-get update && apt-get install -y unzip
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /tmp
COPY composer.* ./
RUN composer install
# Move vendor dependencies into Wordpress' source folder
RUN mv vendor /usr/src/wordpress

### Wordpress Customization ###

# Copy custom plugins into the tmp folder
# COPY wordpress/wp-content/plugins/ wp-content/plugins/

# Replace wp-contents into Wordpress' source
RUN cp -R --parents wp-content/* /usr/src/wordpress/

# Copy custom configuration file into the public html folder
COPY wordpress/wp-config.php /var/www/html

# Needed for Wordpress' entrypoint
WORKDIR /var/www/html

# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
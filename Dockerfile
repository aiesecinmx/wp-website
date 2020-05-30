FROM wordpress:5.4.1-php7.4-apache

# Use the PORT environment variable in Apache configuration files.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Install Wordpress plugins with Composer into a temporary folder
RUN apt-get update && apt-get install -y unzip
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /tmp
COPY composer.* ./
RUN composer install
# Move vendor dependencies into Wordpress' source folder
RUN mv vendor /usr/src/wordpress

### Wordpress Customization ###

# Copy custom configuration file into the public html folder
COPY wordpress/wp-config.php /var/www/html

# Copy custom plugins and themes into the tmp folder
# COPY wordpress/wp-content/plugins/ wp-content/plugins/
# COPY wordpress/wp-content/themes wp-content/themes

# Replace default plugins in Wordpress' source
RUN rm -rf /usr/src/wordpress/wp-content/plugins && \
    mv wp-content/plugins /usr/src/wordpress/wp-content

# Update default language support into Wordpress' source
RUN mv wp-content/languages /usr/src/wordpress/wp-content

# Needed for Wordpress' entrypoint
WORKDIR /var/www/html

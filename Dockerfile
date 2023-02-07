# Use an official PHP image as the base image
FROM php:7.4-apache

RUN a2enmod rewrite

# Copy the contents of the application to the /var/www/html directory in the container
COPY . /var/www/html

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Expose port 80 to allow incoming connections to the container
EXPOSE 80

# Start the Apache web server when the container is run
CMD ["apache2-foreground"]
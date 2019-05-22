FROM silintl/php7

# Copy an Apache vhost file into sites-enabled. This should map
# the document root to whatever is right for your app
COPY vhost-config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /data
VOLUME ["/data"]
RUN mkdir -p /data/www/
RUN mkdir  -p /data/www/src/

# Copy your application source into the image
COPY src/ /data/www/src
COPY index.php /data/www/
COPY .htaccess /data/www/
COPY composer.json /data/www/

WORKDIR /data/www
RUN composer install --no-dev
EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]

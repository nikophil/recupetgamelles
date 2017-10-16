FROM c2is/php-fpm:symfony-composer

ADD symfony.ini /etc/php5/fpm/conf.d/
ADD symfony.ini /etc/php5/cli/conf.d/

RUN apt-get update && apt-get -y install
RUN apt-get update && apt-get install -y ca-certificates git-core ssh php5-xdebug php5-intl

#RUN setfacl -R -m u:"www-data":rwX -m u:`whoami`:rwX /var
#RUN setfacl -dR -m u:"www-data":rwX -m u:`whoami`:rwX /var

RUN usermod -u 1001 www-data
RUN groupmod -g 1001 www-data

# Install acl
CMD HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
CMD setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX /var/www/symfony/app/cache /var/www/symfony/app/logs
CMD setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX /var/www/symfony/app/cache /var/www/symfony/app/logs

# Docker
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > /etc/php5/cli/conf.d/blackfire.ini

CMD ["php5-fpm", "-F"]

EXPOSE 9000

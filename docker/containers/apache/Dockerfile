FROM c2is/apache

ADD ./recupetgamelles.conf /etc/apache2/sites-available/
RUN a2enmod vhost_alias
RUN /bin/ln -sf ../sites-available/recupetgamelles.conf /etc/apache2/sites-enabled/

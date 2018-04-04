#!/usr/bin/env sh

DC_FILE=${DC_FILE:-docker-compose.yml}

composer () {
    docker-compose -f ${DC_FILE} exec --user=www-data php composer --working-dir=/var/www/symfony $*
}

console () {
    docker-compose -f ${DC_FILE} exec --user=www-data php php /var/www/symfony/bin/console $*
}

coverage() {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/vendor/bin/phpunit --coverage-html /var/www/symfony/app/Resources/tests
}

grunt () {
    docker-compose -f ${DC_FILE} run --rm --user=node node grunt $*
}

humbug () {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/vendor/bin/humbug $*
}

node () {
    docker-compose -f ${DC_FILE} exec nodejs $*
}

npm () {
    docker-compose -f ${DC_FILE} run --rm --user=node node npm $*
}

encore () {
    docker-compose -f ${DC_FILE} run --rm --user=node node encore $*
}

phpunit () {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/vendor/bin/simple-phpunit $*
    git co app/Resources/google_auth/credentials.json
}

phpunitNoInit () {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/vendor/bin/phpunit --bootstrap /var/www/symfony/app/tests-noinit.bootstrap.php $*
}

dsu () {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console doctrine:schema:update --force
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console doctrine:schema:validate
}

stopWatch () {
    docker stop $(docker ps | grep easycomptafiscalsearch_node_run | grep -o '[0-9a-z_]*$')
}

lexik () {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console lexik:translations:import -c -f
}

dcup () {
    docker-compose -f ${DC_FILE} up -d --build
    docker-compose -f ${DC_FILE} ps
}

dcbuild () {
    docker-compose -f ${DC_FILE} pull
    docker-compose -f ${DC_FILE} build
}

rebase () {
    git fetch -p
    git rebase -p origin/master
}

emptyTmp () {
    docker-compose exec php /bin/sh -c "rm -rf /tmp/*"
}

df () {
    docker-compose exec php df -h
}

init () {
    dcup
    npm install
    composer config extra.symfony.allow-contrib true
    composer install
    fixtures
}

fixtures () {
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console doctrine:database:create --if-not-exists
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console doctrine:schema:drop --force --full-database
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console doctrine:schema:update --force
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console doctrine:schema:validate
    docker-compose -f ${DC_FILE} exec --user=www-data php /var/www/symfony/bin/console app:import:fiscal-data
    git checkout app/Resources/google_auth/credentials.json
}

$*

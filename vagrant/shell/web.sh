#!/bin/sh

SOURCE_PATH=/var/www/springconsult-admin.loc

apt-get -y update
apt-get -y install git vim rsync curl

echo "Installing Apache2..."
apt-get install -y apache2
cp ${SOURCE_PATH}/vagrant/configs/springconsul-admin.loc /etc/apache2/sites-available/springconsult-admin.loc.conf
ln -s /etc/apache2/sites-available/springconsult-admin.loc.conf /etc/apache2/sites-enabled/
apt-get install -y apache2-mpm-itk
service apache2 restart

echo "Installing PHP..."
apt-get install -y language-pack-en-base
LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

apt-get update
#apt-get install -y php5.6 php5.6-fpm php5.6-cli libapache2-mod-php5.6 php5.6-common php5.6-intl php5.6-json php5.6-mysql php5.6-gd php5.6-imagick php5.6-curl php5.6-mcrypt php5.6-dev php5.6-xdebug
#
#cp ${SOURCE_PATH}/vagrant/configs/20-xdebug.ini /etc/php5.6/fpm/conf.d/
#cp ${SOURCE_PATH}/vagrant/configs/20-xdebug.ini /etc/php5.6/apache2/conf.d/

apt-get install unzip
apt-get install -y php7.1 php7.1-fpm php7.1-cli php7.1-common php7.1-intl php7.1-json php7.1-mysql php7.1-gd php7.1-zip php7.1-imagick php7.1-curl php7.1-mcrypt php7.1-dev php7.1-opcache php-xdebug php7.1-mbstring php7.1-xml

cp ${SOURCE_PATH}/vagrant/configs/20-xdebug.ini /etc/php7.1/fpm/conf.d/
cp ${SOURCE_PATH}/vagrant/configs/20-xdebug.ini /etc/php7.1/apache2/conf.d/

cp ${SOURCE_PATH}/vagrant/configs/php7.ini /etc/php7.1/fpm/php.ini
cp ${SOURCE_PATH}/vagrant/configs/php7.ini /etc/php7.1/cli/php.ini

a2enmod rewrite

service apache2 restart
service php7.1-fpm restart

echo "mysql-server mysql-server/root_password password renault2004" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password renault2004" | debconf-set-selections
apt-get  install -y mysql-server mysql-client

echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
chmod 777 /usr/local/bin/composer

HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`

echo "Downloading all needed PHP libraries... Please, set correct variables into parameters.yml or run 'composer install' and set them there."
cd ${SOURCE_PATH}
composer install

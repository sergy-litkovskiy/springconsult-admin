#!/bin/sh

SOURCE_PATH=/var/www/springconsult_admin.loc

apt-get -y update
apt-get -y install git vim rsync curl

echo "Installing Apache2..."
apt-get install -y apache2
cp ${SOURCE_PATH}/bin/vagrant/configs/springconsul_admint.loc /etc/apache2/sites-available/springconsult_admin.loc.conf
ln -s /etc/apache2/sites-available/springconsult_admin.loc.conf /etc/apache2/sites-enabled/
apt-get install -y apache2-mpm-itk
service apache2 restart

echo "Installing PHP..."
apt-get install -y php5 php5-fpm php5-cli libapache2-mod-php5 php5-common php5-intl php5-json php5-mysql php5-gd php5-imagick php5-curl php5-mcrypt php5-dev php5-xdebug
cp ${SOURCE_PATH}/vagrant/configs/20-xdebug.ini /etc/php5/fpm/conf.d/
cp ${SOURCE_PATH}/vagrant/configs/20-xdebug.ini /etc/php5/apache2/conf.d/

echo "mysql-server mysql-server/root_password password renault2004" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password renault2004" | debconf-set-selections
apt-get  install -y mysql-server mysql-client

echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

#echo "Installing phpUnit..."
#wget https://phar.phpunit.de/phpunit.phar
#chmod +x phpunit.phar
#mv phpunit.phar /usr/local/bin/phpunit

#echo "Installing redis"
#apt-add-repository ppa:chris-lea/redis-server
#apt-get update
#apt-get install -y redis-server

#echo "Installing gearmand"
#apt-get -y install gearman
#sudo pecl install gearman
#sudo gearmand -d

HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`

echo "Downloading all needed PHP libraries... Please, set correct variables into parameters.yml or run 'composer install' and set them there."
cd ${SOURCE_PATH}
composer install

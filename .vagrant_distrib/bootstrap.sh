#!/bin/bash

export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
locale-gen en_US.UTF-8

apt-get update
# установка пакетов
apt-get install -y nginx php5-fpm php5-cgi php5-cli
# установка root-пароля для mysql перед установкой libmysqlclient
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
apt-get install -y mysql-server php5-mysql

# создаем БД, заливаем дамп
mysql -uroot -proot -e'CREATE DATABASE IF NOT EXISTS `jcat` DEFAULT CHARACTER SET `utf8`;'
# mysql -uroot -proot region < /vagrant/protected/data/schema.region.sql
# создаем пользователя
mysql -uroot -proot -e'GRANT ALL PRIVILEGES ON jcat.* TO "jcat"@"%" IDENTIFIED BY "diquwhoj";'
# устанавливаем виртуальный хост в nginx
cp /vagrant/.vagrant_distrib/nginx_vhost.conf /etc/nginx/sites-available/vhost
if [ -f /etc/nginx/sites-enabled/default ]
then
    rm /etc/nginx/sites-enabled/default
fi
ln -s /etc/nginx/sites-available/vhost /etc/nginx/sites-enabled/vhost
# копируем nginx.conf (главное отличие от дефолтного - "sendfile off;") без этого проблемы с отображением js-файлов
cp /vagrant/.vagrant_distrib/nginx.conf /etc/nginx/
service nginx restart



# конфиги
# cp /vagrant/.vagrant_distrib/php_conf/protected/* /vagrant/protected/config/
cd /vagrant
# применяем все миграции
#php protected/yiic.php migrate --interactive=0

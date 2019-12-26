#!/usr/bin/env bash
# Vagrant provosion script for ubuntu/bionic

# Update ubuntu/bionic installation sources list. You can comment certbot's repository
sudo add-apt-repository ppa:certbot/certbot
sudo apt-get update -y
sudo apt-get upgrade -y

# Installing Mail Subsystem. You can comment this section
sudo hostnamectl set-hostname YOUR-HOST.NAME
echo postfix postfix/mailname string $HOSTNAME | sudo debconf-set-selections
echo postfix postfix/main_mailer_type string 'Internet Site' | sudo debconf-set-selections
sudo apt-get install -y postfix
sudo apt-get install -y opendkim
sudo apt-get install -y dovecot-imapd
sudo apt-get install -y incron

# Installing DNS. You can comment this section
sudo apt-get install -y bind9

# Installing Time Syncronization. You can comment this section
sudo apt-get install -y ntp ntpdate
sudo ntpdate pool.ntp.org
sudo rm /etc/localtime
sudo ln -s /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime
sudo systemctl reload ntpd

# Installing Midnight Commander. You can comment this section
sudo apt-get install -y mc catdoc
sudo sed -i '$ a \editor = mcedit\n' /etc/incron.conf

# Installing Nginx with PHP-FPM. You can comment certbot installation 
sudo apt-get install -y nginx
sudo apt-get install -y certbot python-certbot-nginx
sudo apt-get install -y php-fpm php-imap php-mbstring php-xml php-tidy php-gd php-zip
sudo apt-get install -y composer

# Unpacking custom server settings from etc.tgz
sudo tar -xzf /vagrant/etc.tgz -C /

# Reload nginx. Add mail/dns services reloading if need or just reboot the VM
sudo systemctl reload nginx

# Creating user and installing the site
sudo useradd -m -U fp
sudo install -g fp -o fp -d /var/www/fprognoz.org
sudo ln -s /var/www/fprognoz.org /home/fp/fprognoz.org
su -c 'cd ~fp
composer require google/recaptcha
composer require phpmailer/phpmailer
composer require mpdf/mpdf
composer require phpoffice/phpspreadsheet
tar -xzf /vagrant/data.tgz
git config --global user.name "Your Name"
git config --global user.email "your@e-mail"
git clone https://github.com/alexander-sessa/fprognoz.org.git fprognoz.org
tar -xzf /vagrant/images.tgz -d fprognoz.org' fp

# Set cron jobs
sudo crontab /vagrant/cron -u fp
sudo incrontab /vagrant/incron -u fp

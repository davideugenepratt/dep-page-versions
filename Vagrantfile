# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/xenial64"

  config.vm.network "private_network", ip: "192.168.13.13"

  config.vm.provision "shell", inline: <<-SHELL

    apt-get -y update

    sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
    sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

    sudo apt-get install -y mysql-server php7.0 libapache2-mod-php7.0 php7.0-cli php7.0-common php7.0-mbstring php7.0-gd php7.0-intl php7.0-xml php7.0-mysql php7.0-mcrypt php7.0-zip
    
    curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
    sudo chmod +x phpcs.phar 
    sudo mv phpcs.phar /usr/local/bin/phpcs

    curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
    sudo chmod +x phpcbf.phar 
    sudo mv phpcbf.phar /usr/local/bin/phpcbf

    wget https://phar.phpunit.de/phpunit-6.5.phar
    sudo chmod +x phpunit-6.5.phar
    sudo mv phpunit-6.5.phar /usr/local/bin/phpunit

    curl -Ss https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/bin/composer
    composer global require wp-coding-standards/wpcs
  SHELL

end

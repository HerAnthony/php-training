# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.

Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://vagrantcloud.com/search.
  config.vm.box = "ubuntu/xenial64"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # NOTE: This will enable public access to the opened port
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine and only allow access
  # via 127.0.0.1 to disable public access
  # config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  config.vm.synced_folder ".", "/vagrant", :group => "www-data", :mount_options => ['dmode=775', 'fmode=664']

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  # config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
  #   vb.memory = "1024"
  # end
  #
  # View the documentation for the provider you are using for more
  # information on available options.

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use


  config.trigger.before :destroy do |trigger|
    trigger.name = "Impossible trigger, Pre-Destroy"
    trigger.run_remote = { inline: "rm /vagrant/test.bak" }
    trigger.run_remote = { inline: "mv /vagrant/test.sql /vagrant/test.bak" }
    trigger.run_remote = { inline: "mysqldump -u root -psecret test > /vagrant/test.sql" }
    trigger.on_error = :continue
  end

  config.vm.provision "shell", inline: <<-SHELL
     echo "----------------UPDATES--------"
     sudo apt-get update -y
     sudo apt-get upgrade -y
     echo "----------------APACHE2--------"
     sudo apt-get install apache2 -y
     sudo rm -rf /var/www/html
     sudo ln -fs /vagrant /var/www/html
     echo "----------------MYSQL--------"
     sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password secret'
     sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password secret'
     sudo apt-get -y install mysql-server
     mysql -u root -psecret -e "CREATE DATABASE IF NOT EXISTS test"
     mysql -u root -psecret test < /vagrant/test.sql
     echo "----------------PHP--------"
     sudo apt-get install php7.0 libapache2-mod-php7.0 php7.0-curl php7.0-cli php7.0-dev php7.0-gd php7.0-intl php7.0-mcrypt php7.0-json php7.0-mysql php7.0-opcache php7.0-bcmath php7.0-mbstring php7.0-soap php7.0-xml php7.0-zip -y
     echo "----------------APACHE2-RESTART--------"
     sudo service apache2 restart
     echo "----------------UPDATES-AGAIN--------"
     #sudo apt-get update -y
     #sudo apt-get upgrade -y
     echo "----------------PHPMYADMIN--------"
     sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
     sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password secret'
     sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password secret'
     sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password secret'
     sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect none'
     sudo apt-get install -y phpmyadmin
     sudo ln -fs /usr/share/phpmyadmin /vagrant/phpmyadmin
     echo "----------------APACHE2-RESTART--------"
     sudo a2enmod rewrite
     sudo sed -i '/<Directory \\/var\\/www\\/>/,/<\\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
     sudo service apache2 restart
   SHELL
end
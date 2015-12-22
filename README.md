# php-url-shortener-without-framework
php-url-shortener-without-framework

## Requirements ##
1. OS Ubuntu/Debian (last stable version)
2. virtualbox (https://www.virtualbox.org/wiki/Linux_Downloads)
3. vagrant (https://www.vagrantup.com/downloads.html)
4. base knowledge of virtualbox, vagrant, linux console, git

## Installation ##
We will download and install vagrant image and then go to the virtual machine and git clone test task repository.

1. Run Linux console
2. Type cd /var/www/
3. Follow "Installation" steps from https://github.com/rlerdorf/php7dev. vagrant ssh - must be last command. See next step.
4. makephp 7
5. sudo rm -rf /var/www/default
6. mkdir -p /var/www/default 
7. git clone https://github.com/ViktorZharina/php-url-shortener-without-framework.git /var/www/default/
8. sudo nano /etc/apache2/sites-enabled/000-default.conf
 * change string "DocumentRoot /var/www/default" to "DocumentRoot /var/www/default/public"
 * change string "<Directory /var/www/default/>" to "<Directory /var/www/default/public/>"
 * change string "AllowOverride None" to "AllowOverride all"
9. sudo a2enmod rewrite
10. sudo service nginx stop 
11. sudo service php-fpm stop 
12. sudo service apache2 start
13. mysql -u root 
  * CREATE DATABASE `urlshortener` CHARACTER SET utf8 COLLATE utf8_general_ci;
  * GRANT ALL PRIVILEGES ON *.* TO root@localhost IDENTIFIED BY 'toor' WITH GRANT OPTION;
  * FLUSH PRIVILEGES;
  * quit
14. mysql -u root -ptoor urlshortener < /var/www/default/urlshortener.sql
15. exit
16. exit
16. Add this to your hosts file (/etc/hosts): 192.168.7.7 php7dev 
16. Go to a browser and type http://php7dev/
17. Test service
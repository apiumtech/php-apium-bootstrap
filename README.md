php-apium-bootstrap
===================

Enterprise custom php framework to demonstrate design patterns by example




Installation
============

1.Edit the hosts file to include

127.0.0.1   phpsample.localhost


2.Edit your apache sites file:

<VirtualHost *:80>

    ServerName phpsample.localhost
    DocumentRoot /home/youruser/workspace/php-apium-bootstrap/www
    php_value auto_prepend_file /home/xavi/workspace/php-apium-bootstrap/config/phpIni.conf.php

    <Directory /home/youruser/workspace/php-apium-bootstrap>
    	RewriteEngine On
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Require all granted
	</Directory>

</VirtualHost>
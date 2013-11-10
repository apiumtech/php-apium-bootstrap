php-apium-bootstrap
===================

Enterprise custom php framework to demonstrate design patterns by example

This framework comes as 'it is', for learning purposes with the basis of a well layered php application.


Installation
------------

* Edit the hosts file to include

127.0.0.1   phpsample.localhost


* Edit your apache sites file:
`
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
`


Run the tests
-------------

* Go to .../php-apium-bootstrap/test/testsSuites/AllTestsSuite.php and run AllTestsSuite

that must return something like:

`
PHPUnit 3.6.10 by Sebastian Bergmann.
Time: 0 seconds, Memory: 4.50Mb
OK (20 tests, 26 assertions)
`



Copyright and license
---------------------

Copyright 2013 Apiumtech.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.



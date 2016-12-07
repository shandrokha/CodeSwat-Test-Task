CodeSwat Test Task
========================

Installation
--------------

1. Configure the application.
	- Make the 'var' directory writable
	- Run 'composer install'
	- Edit 'app/config/parameters.yml'
	- Run 'php bin/console doctrine:database:create'
	- Run 'php bin/console doctrine:schema:create'
	- Run 'php bin/console doctrine:fixtures:load --fixtures=src/CodeSwat/DataFixtures/ORM/LoadData.php'

2. Start the server.
	- Run 'php bin/console server:run'

3. Open the application.
	- Open 'http://127.0.0.1:8000/products' in your browser
	

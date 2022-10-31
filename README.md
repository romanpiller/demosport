Demosport
===========
demo 

Installation
------------
The best way to install Web Project is using Composer. If you don't have Composer yet,
download it following [the instructions](https://doc.nette.org/composer). Then use command:

  composer create-project nette/sandbox path/to/install
	cd path/to/install


Make directories `temp/` and `log/` writable.

Database Connection
-------------------
Please set credential for database in `config/prod.neon`


Web Server Setup
----------------
The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the welcome page.


Requirements
------------ 
- min PHP 8.0 


Create DB
----------
Yuo have to set MySql mode, deactivated `NO_ZERO_IN_DATE` and `NO_ZERO_DATE`
Run with root `SET GLOBAL sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';`
Make file executable `project_folder/bin/lnx/createDb.sh`  with command `chmod +x createDb.sh`  
Run scipt `./createDb.sh` , enter root password when prompted and two databases `sportdb`  , `sportdbtest`  are created. 


Load Data to DB - csv file
-----------------------------
Make file executable `project_folder/bin/lnx/loadCsv.sh`  with command `chmod +x loadCsv.sh` 
Run scipt `./loadCsv.sh` , enter root password when prompted and data are loaded. 
Data are loaded to `sportdb` and `sportdbtest` database.
After loading, the number of loaded rows will be returned to you.


Load Data to DB - sql file
----------------------------
Make file executable `bin/lnx/loadSql.sh`  with command `chmod +x loadSql.sh` 
Run scipt `./loasSql.sh` , enter root password when prompted and data are loaded. 
Data are loaded to `sportdb` and `sportdbtest` database.


Unit tests
----------
Before test you have to set credential for test database `config/test.neon`

Use alias command `composer test`


Static analyse
---------------

Use alias command `composer phpstan`

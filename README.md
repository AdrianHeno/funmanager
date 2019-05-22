FunManager
==========



LOCAL DEVELOPMENT
-----------------
 * `docker-compose up`
 ** There are 2 containers `funmanager_mysql` is the database and `funmanager_web` is the application.
 ** To connect to either run `docker exec -it <container name> /bin/bash`
 ** To connect to mysql once within `funmanager_mysql` : `mysql -u root  -proot -h funmanager_mysql`
 * Run the SQL import onto the mysql server
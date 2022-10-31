@ECHO OFF
echo "Produkcna DB  heslo pre ucet root"
echo .
mysql -h localhost -P 3306 -u root -p sportdb < ../data.sql
echo "Testovacia DB  heslo pre ucet root"
echo .
mysql -h localhost -P 3306 -u root -p sportdbtest < ../data.sql



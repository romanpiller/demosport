echo "Produkcna DB zadaj heslo pre root: "
mysql -h localhost -P 3306 -u root -p sportdb < ../data.sql
echo "Testovacia DB zadaj heslo pre root: "
mysql -h localhost -P 3306 -u root -p sportdbtest < ../data.sql

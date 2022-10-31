@ECHO OFF
echo "Zadaj heslo do DB pre ucet root"
echo .
mysql -h localhost -P 3306 -u root -p < ..\databaza.sql
pause

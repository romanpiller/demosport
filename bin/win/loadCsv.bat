@ECHO OFF
echo "Zadaj heslo do DB pre ucet root"
echo .
mysql --local-infile -h localhost -u root -p < ../loadFile-win.sql
pause
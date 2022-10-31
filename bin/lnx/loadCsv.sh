echo "Zadaj heslo do DB pre ucet root"
mysql --local-infile -h localhost -u root -p < ../loadFile-lnx.sql


#!/bin/sh

host='localhost'
db='dhvanyaloka'
usr='root'
pwd='mysql'

echo "CREATE DATABASE  IF NOT EXISTS $db CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;" | /usr/bin/mysql -u $usr -p$pwd

echo "Book Insertion...."
perl books_toc.pl $host $db $usr $pwd

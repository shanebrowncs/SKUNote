# SKUNote
A simple note taking application for businesses with large and changing inventories

![SKU Note](https://shane-brown.ca/img/c19e8ca.png)

# Features
* Search by SKU number, sorted by date/time
* Easy form submition, minimal info needed

# Dependencies
```
General LAMP stack
phpnd - MySQL native driver for PHP
```


# Installation
* Clone repository to web root or desired hosting location
* Ensure web access to 'private' folder is denied (a .htaccess is given by default, but please test that it works before moving on)
* Create file in private folder named `mysql.ini`
* Put mysql user info in like below
```
[mysql]
host = localhost
user = *USER*
password = *PASSWORD*
database = skunote
```
* Create mysql database corresponding to mysqli.ini's database variable (skunote).
* Create table named `notetable` with columns `id[int(11)], sku[int(11)], user[varchar(128)], note[varchar(512)], date[datetime]`

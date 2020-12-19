# SKUNote
A simple note taking application for businesses with large and changing inventories

## Basic View
![SKU Note](https://shanebrown.ca/img/c19e8ca.png)

## Divisions
![Divisions](https://shanebrown.ca/img/383d24f.png)

# Features
* Search by SKU number, sorted by date/time
* Easy form submission, minimal info needed
* Divisions: Optional feature where you can separate and filter notes based on departments in your company or by office locations

# Dependencies
```
General LAMP stack
phpnd - MySQL native driver for PHP
```


# Installation
* Clone repository to web root or desired hosting location
* Ensure web access to 'private' folder is denied (a .htaccess is given by default, but please test that it works before moving on)
* Create file in 'private' folder named `mysql.ini`
* Put mysql user info in like below
```
[mysql]
host = localhost
user = *USER*
password = *PASSWORD*
database = skunote
```
* Create mysql database corresponding to mysqli.ini's database variable (skunote).
* Import `DB_DIVISIONS_SKELETON.sql` into database to create proper layout

# Enable Divisions
* Open `private/config.ini`
* Change `divisions = no` to `divisions = yes`

# Migrating 1.0 -> 2.0
The 2.0 API including Divisions is NOT backwards compatible with the 1.0 database schema. When upgrading to 2.0 you will have to update your database schema using the following steps before updating the PHP API.

* Backup your current MYSQL database
* Check that it restores properly
* Check again
* Inspect the `DIVISIONS_SQL_UPGRADE.sql` script.
* Once you understand what it does execute it on your MYSQL database or modify it to fit your needs (you are responsible for any scripts you execute)
* Ensure a new table "divisions" has been created with a single entry named "default"
* Ensure each entry in the table "notetable" has a new cell "division" with a value corresponding to the id of the "default" row in the "divisions" table
* Install the new API version into your webroot. Delete any unneeded files such as the example .sql files

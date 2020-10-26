# INSTALLING

This project was originally developed to run on versions of FreeBSD or Ubuntu
Linux that have reached or is nearing end-of-life status. The information
provided in this document refers to the most recent platform that the
application had been deployed on: Ubuntu Linux 16.04 LTS (amd64).

## System Package Requirements

The following packages, along with any required dependencies, will need to
be installed using the `apt-get` utility on Ubuntu:

- apache2
- libapache2-mod-php7.0
- libmysqlclient-dev
- libmysqlclient20
- php-cli
- php-common
- php-gd
- php-mbstring
- php-mysql
- php-pear
- php-zip

## Apache Module Requirements

The following Apache modules need to be installed and enabled:

- alias
- rewrite

## PEAR Package Requirements

The following PHP PEAR packages need to be installed using the `pear install`
command:

- DB-1.10.0
- Image_Graph-0.8.0
- Image_Canvas-0.3.5
- MDB2-2.5.0b5
- MDB2_Driver_mysqli-1.5.0b4

Both of the packages listed are alpha or beta versions and, depending on the
version of `pear` installed on the system and how it is configured, you may
need to allow installation of packages in `alpha` or `beta` state.

## Apache Configuration

Depending on how you want to serve the web application through Apache 2.x,
either as a dedicated site or a virtual directory, the following should be
included in the appropriate Apache configuration file:

```
RewriteEngine On
DirectoryIndex index.php

<Directory "/path/to/wwdt.me_v2">
    Options -Indexes +FollowSymLinks -MultiViews -Includes
    AllowOverride all
    Order allow,deny
    Allow from all
</Directory>
```

## Application Configuration

Before the application can be used, the are several files that need to be
copied or renamed, and modified with the correct path and database information.

### stats/_includes/db_conn.dist.php

This file need to be copied or renamed to `stats/_includes/db_conn.php` and the
database connection information needs to be filled out and the `SITE_PATH`
value needs to point to the fully-qualified path of the appliation.

### s/r.dist.php

This file needs to be copied or renamed to `s/r.php` and the database
connection information needs to be filled out.

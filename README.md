calendar_app
============

PHP Crossbar Framework Based Calendar App

Following configurations are required to setup the calendar app on a mac.

-> Added virtual host in: /etc/apache2/extra/httpd-vhosts.conf

<VirtualHost *:80>
    DocumentRoot "/Users/yamin/Sites/calendar_app/htdocs"
    ServerName calendar.local
    ServerAlias *.calendar.local
    AllowEncodedSlashes On

    <Directory "/Users/yamin/Sites/calendar_app/htdocs">
        Options FollowSymLinks Indexes
        AllowOverride None

        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteRule !\.(js|ico|gif|jpg|png|css|html)$ index.php
        </IfModule>
    </Directory>

    LogLevel error
    ErrorLog "/private/var/log/apache2/calendar.local-error.log"
    CustomLog  "/private/var/log/apache2/calendar.local-access_log" common
        
    ServerAdmin help@mydomain.com
</VirtualHost>

-> Added the domain in: /etc/hosts:

127.0.0.1   calendar.local

-> sudo apachectl restart

-> Run calendar_app.sql to import into mysql database (calendar_app)

-> Update Mysql login credentials in the htdocs/index.php


Thanks
Yamin Noor

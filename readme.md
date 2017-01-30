# Global Archive v2
### commands  : 
#### To migrate all data from mysql to pgsql
```
php artisan archive:migrate
```
#### To migrate all HI8 data
```
php artisan archive:hi8
```
#### To migrate all Photos data
```
php artisan archive:photos
```

#### To start the Application
```
php artisan serve --port 80  //needs Sudo Access
```

#### For help and other commands
```
php artisan 
```

### Adding Scheduler Commands
```
crontab -e
```
and then add the command
```
* * * * * /usr/local/bin/php /home/archive/artisan schedule:run
```

### Server Configuration

```
<VirtualHost *:8091>

ServerAdmin you@example.com
ServerName localhost:8091

DocumentRoot "/home/likewise-open/COE1/poovarasanv/learn/archive/public"
<Directory "/home/likewise-open/COE1/poovarasanv/learn/archive/public">
    DirectoryIndex index.php
         AllowOverride All
         Require all granted
         Order allow,deny
         Allow from all
</Directory>
</VirtualHost>

```
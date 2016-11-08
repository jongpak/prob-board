# Prob/Board
*A simple web board based on [prob-framework](https://github.com/jongpak/prob-framework)*

## Installation
### Copy sample configuration
```
> cp .htaccess.example .htaccess
> cp config/site.php.example config/site.php
> cp config/db.php.example config/db.php
> cp app/Auth/config/accounts.php.example app/Auth/config/accounts.php
```

### Setting configuration for your environment
.htaccess
```
RewriteBase ** YOUR_WEB_SITE_URL **
```

config/site.php
```php
'url' => '/',
'publicPath' => '/public/',
```

config/db.php
```php
'host'      => 'localhost',
'port'      => 3306,
'user'      => 'username',
'password'  => 'password',
'dbname'    => 'dbname',
'charset'   => 'utf8'
```

app/Auth/config/accounts.php
```php
return [
    // ...

    'test' => [
        'password' => 'test',
        'role' => [ 'Member' ]
    ],

    //*** Add YOUR ACCONUTS
];
```

### Making directories
```
> mkdir data
> mkdir data/attachment
```

### Dependency package update (use Composer)
```
> ./composer.phar update
```

### Creating table schema
```
> php ./vendor/doctrine/orm/bin/doctrine.php orm:schema-tool:create
```

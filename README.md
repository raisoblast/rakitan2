![rakitan2](public/asset/img/rakitan.png)

# rakitan2
Very simple component-based PHP framework

## Base Component
 - HTTP layer:  [zendframework/zend-diactoros](https://packagist.org/packages/zendframework/zend-diactoros)
 - Middleware: [PSR-15 HTTP Middleware](https://github.com/php-fig/fig-standards/tree/master/proposed/http-middleware)
 - Middleware dispatcher: [oscarotero/middleland](https://packagist.org/packages/oscarotero/middleland)
 - Dependecy injector: [php-di/php-di](https://packagist.org/packages/php-di/php-di)
 - Router: [altorouter/altorouter](https://packagist.org/packages/altorouter/altorouter)
 - Template engine: [league/plates](https://packagist.org/packages/league/plates)

## Installation
 - Clone the repo
```bash
$ git clone https://github.com/raisoblast/rakitan2.git
$ cd rakitan2
$ composer install
```

 - Run using php built-in web server:
```bash
$ cd public
$ php -S localhost:8000
```
Open ```http://localhost:8000``` in a browser.

### Web server configuration
#### Apache
mod_rewrite must be enabled
 - apache 2.2
```apache
<Directory "/var/www/rakitan">
    AllowOverride All
    Order allow,deny
    Allow from All
</Directory>
```

 - apache 2.4
```apache
<Directory "/var/www/rakitan">
    AllowOverride All
    Require all granted
</Directory>
```

## Documentation
not yet

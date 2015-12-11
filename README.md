ZfSnapVarConfig [![Build Status](https://travis-ci.org/snapshotpl/ZfSnapVarConfig.svg?branch=master)](https://travis-ci.org/snapshotpl/ZfSnapVarConfig)
===============

Variables in configuration for Zend Framework 2

Usage
-----

Sometimes you want to use same values in other places in your config. What are you doing?

```php
<?php
$myIp = '127.0.0.1';
return array(
  'db' => $myIp,
  'memcache' => $myIp,
);
```

Of course it's working, but when you want to share `$myIp` between separate config files thats challange! ZfSnapVarConfig make a magic here!

```php
return array(
  'ips' => array(
    'local' => '127.0.0.1',
    'memcache' => '127.0.0.2',
    'smtp' => '127.0.0.3'
  ),
  'email' => 'your@email.com',
  'db' => new \ZfSnapVarConfig\StringSeparated('ips.local'),
  'memcache' => new \ZfSnapVarConfig\StringSeparated('ips|memcache', '|'),
  'email' => array(
    'smtp' => new \ZfSnapVarConfig\ArrayList(['ips', 'smtp']),
    'default-mail' => new \ZfSnapVarConfig\StringSeparated('email'),
    'reply-to' => new \ZfSnapVarConfig\StringSeparated('email'),
  ),
);
```

```php
return array(
  'form' => array(
    'address' => new \ZfSnapVarConfig\StringSeparated('email'),
  ),
);
```

On this moment you can use two selectors:
* `ZfSnapVarConfig\StringSeparated`
* `ZfSnapVarConfig\ArrayList`

You can write your own selectors very easy. Just implement `ZfSnapVarConfig\VarConfigInterface` or extend `ZfSnapVarConfig\AbstractVarConfig`!

How to install?
---------------
Via [composer.json](https://getcomposer.org/)
```json
{
    "require": {
        "snapshotpl/zf-snap-var-config": "~1.0"
    }
}
```

and remember to add `ZfSnapVarConfig` module to your `application.config.php` file! :-)

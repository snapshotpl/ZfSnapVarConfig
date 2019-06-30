ZfSnapVarConfig [![Build Status](https://travis-ci.org/snapshotpl/ZfSnapVarConfig.svg?branch=master)](https://travis-ci.org/snapshotpl/ZfSnapVarConfig)
===============

Variables in array.

Additional provides module for Zend Framework 2 and 3 for configurations.

Usage
-----

Sometimes you want to use same values in other places in your config. What are you doing?

```php
<?php
$myIp = '127.0.0.1';
return [
    'db' => $myIp,
    'memcache' => $myIp,
];
```

Of course it's working, but when you want to share `$myIp` between separate config files thats challange! ZfSnapVarConfig make a magic here!

```php
$data = [
    'ips' => [
        'local' => '127.0.0.1',
        'memcache' => '127.0.0.2',
        'smtp' => '127.0.0.3'
    ],
    'email' => new ZfSnapVarConfig\Value\Env('ADMIN_EMAIL'),
    'db' => ZfSnapVarConfig\Value\Path::fromString('ips/local'),
    'memcache' => ZfSnapVarConfig\Value\Path::fromString('ips|memcache', '|'),
    'email' => [
        'smtp' => ZfSnapVarConfig\Value\Path::fromArray(['ips', 'smtp']),
        'default-mail' => ZfSnapVarConfig\Value\Path::fromString('email'),
        'reply-to' => ZfSnapVarConfig\Value\Path::fromString('email'),
        'other-address' => new ZfSnapVarConfig\Value\Path('ips', 'smtp'),
    ],
];

$service = new ZfSnapVarConfig\VarConfigService();
$replaced = $service->replace($data); // or $service($data);

assertSame([
    'ips' => [
        'local' => '127.0.0.1',
        'memcache' => '127.0.0.2',
        'smtp' => '127.0.0.3'
    ],
    'email' => 'your@email.com',
    'db' => '127.0.0.1',
    'memcache' => '127.0.0.2',
    'email' => [
        'smtp' => '127.0.0.3',
        'default-mail' => 'your@email.com',
        'reply-to' => 'your@email.com',
        'other-address' => '127.0.0.3',
    ],
], $replaced);
```

On this moment you can use selectors:
* `ZfSnapVarConfig\Value\Path`
* `ZfSnapVarConfig\Value\Env`
* `ZfSnapVarConfig\Value\Callback`

You can write your own value manipulator. Just implement `ZfSnapVarConfig\Value` and create instance in your config.

How to install?
---------------
Via [composer.json](https://getcomposer.org/)
```bash
composer require snapshotpl/zf-snap-var-config
```

If you want to use this library as Zend Framework module add `ZfSnapVarConfig` to your `application.config.php` file.

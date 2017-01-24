[![Build Status](https://travis-ci.org/generationtux/php-healthz.svg?branch=master)](https://travis-ci.org/generationtux/php-healthz)
[![Code Climate](https://codeclimate.com/github/generationtux/php-healthz/badges/gpa.svg)](https://codeclimate.com/github/generationtux/php-healthz)
[![Test Coverage](https://codeclimate.com/github/generationtux/php-healthz/badges/coverage.svg)](https://codeclimate.com/github/generationtux/php-healthz/coverage)

# PHP Healthz
Health checking for PHP apps with built-in support for Laravel.

Get an easy overview of the health of your app! Implement a health check endpoint for load balancers, or your own sanity :) Comes with an optional UI and set of pre-configured checks you can use, and is extensible
to add custom health checks to the stack as well.

- [Setup and usage](#setup)
    - [Laravel](#laravel)
    - [General PHP](#general-php)
- [Available checks and config](#check-configuration)
    - [HTTP](#http-check)
    - [Memcached](#memcached-check)
    - [Debug](#debug-check)
    - [Database (Laravel)](#laravel-database)
    - [Env (Laravel)](#laravel-env)
    - [Queue (Laravel)](#laravel-queue)
- [Creating custom checks](#custom-checks)

----------------------------------------------------------------------------

## Setup

```sh
$ composer require generationtux/healthz
```

### Laravel
(the following should work with Lumen as well, with minor differences)

**Add the service provider that will register the default health checks and routes**
```php
// config/app.php
'providers' => [
    Illuminate...,
    Gentux\Healthz\Support\HealthzServiceProvider::class,
]
```

You should be able to visit `/healthz/ui` to see the default Laravel health checks, or run `php artisan healthz` to get a CLI view.

To add basic auth to the UI page, set the `HEALTHZ_USERNAME` and `HEALTHZ_PASSWORD` environment variables.
Even if the UI has basic auth, the simplified `/healthz` endpoint will always be available to respond with a simple `ok` or `fail` for load balancers and other automated checks to hit.

**In order to customize the health checks, simply register `Gentux\Healthz\Healthz` in your app service provider (probably `app/Providers/AppServiceProvider.php`) to build a custom Healthz instance.**
```php
use Gentux\Healthz\Healthz;
use Illuminate\Support\ServiceProvider;
use Gentux\Healthz\Bundles\Laravel\EnvHealthCheck;
use Gentux\Healthz\Bundles\Laravel\DatabaseHealthCheck;

class AppServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(Healthz::class, function() {
            $env = new EnvHealthCheck();
            $db = new DatabaseHealthCheck();
            $db->setConnection('non-default');

            return new Healthz([$env, $db]);
        });
    }
}
```

[See more about configuring available checks](#check-configuration)

----------------------------------------------------------------------------

### General PHP

**Build an instance of the health check**
```php
<?php
use Gentux\Healthz\Healthz;
use Gentux\Healthz\Bundles\General\MemcachedHealthCheck;

$memcached = (new MemcachedHealthCheck())->addServer('127.0.0.1');
$healthz = new Healthz([$memcached]);
```

**Run the checks and review results**
```php
// @var $results Gentux\Healthz\ResultStack
$results = $healthz->run();

if ($results->hasFailures()) {
    // oh no
}

if ($results->hasWarnings()) {
    // hmm
}

foreach ($results->all() as $result) {
    // @var $result Gentux\Healthz\HealthResult
    if ($result->passed() || $result->warned() || $result->failed()) {
        echo "it did one of those things at least";
    }

    echo "{$result->title()}: {$result->status()} ({$result->description()})";
}
```

**Get the UI view**
```php
$html = $healthz->html();
```

----------------------------------------------------------------------------

## Check configuration

- [HTTP](#http-check)
- [Memcached](#memcached-check)
- [Debug](#debug-check)
- [Database (Laravel)](#laravel-database)
- [Env (Laravel)](#laravel-env)
- [Queue (Laravel)](#laravel-queue)

#### HTTP
<a name="http-check"></a>

#### Memcached
<a name="memcached-check"></a>

#### Debug
<a name="debug-check"></a>

#### Database (Laravel)
<a name="laravel-database"></a>

#### Env (Laravel)
<a name="laravel-env"></a>

#### Queue (Laravel)
<a name="laravel-queue"></a>

----------------------------------------------------------------------------

## Custom checks

*Note: Checks may have one of 3 statuses (`success`, `warning`, or `failure`). Any combination of success and warning and the stack as a whole will be considered to be successful.
Any single failure, however, will consider the stack to be failed.*

To create a custom health check, you should extend `Gentux\Healthz\HealthCheck` and implement the one abstract method `run()`.

```php
<?php

use Gentux\Healthz\HealthCheck;

class MyCustomCheck extends HealthCheck {

    /** @var string Optionally set a title, otherwise the class name will be used */
    protected $title = '';

    /** @var string Optionally set a description, just to provide more info on the UI */
    protected $description = '';

    public function run()
    {
        // any exception that is thrown will consider the check unhealthy
    }
}
```

If no exception is thrown, the check will be presumed to have been successful. Otherwise, the exception's message will be used as the `status` of the failed check.
```php
public function run()
{
    throw new Exception('Heres why the check failed.');
}
```

If you would like the check to show a `warning` instead of a full failure, throw an instance of `Gentux\Healthz\Exceptions\HealthWarningException`.
```php
use Gentux\Healthz\Exceptions\HealthWarningException;

public function run()
{
    throw new HealthWarningException("The check didn't fail, but here ye be warned.");
}
```

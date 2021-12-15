<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        //connection used by the redis facade
        'default' => [
            'url' => env('REDIS_DEFAULT_URL'),
            'host' => env('REDIS_DEFAULT_HOST', '127.0.0.1'),
            'password' => env('REDIS_DEFAULT_PASSWORD'),
            'port' => env('REDIS_DEFAULT_PORT', '6379'),
            //database set to 0 since only database 0 is supported in redis cluster
            'database' => env('REDIS_DEFAULT_DB', 0),
            //redis key prefix for this connection
            'prefix' => 'd:',
        ],

        //connection used by the cache facade when redis cache is configured in config/cache.php
        'cache' => [
            'url' => env('REDIS_CACHE_URL'),
            'host' => env('REDIS_CACHE_HOST', '127.0.0.1'),
            'password' => env('REDIS_CACHE_PASSWORD'),
            'port' => env('REDIS_CACHE_PORT', '6379'),
            //database set to 0 since only database 0 is supported in redis cluster
            'database' => env('REDIS_CACHE_DB', 0),
            //redis key prefix for this connection
            'prefix' => 'c:',
        ],

        //connection used by the session when redis cache is configured in config/session.php
        'session' => [
            'url' => env('REDIS_SESSION_URL'),
            'host' => env('REDIS_SESSION_HOST', '127.0.0.1'),
            'password' => env('REDIS_SESSION_PASSWORD'),
            'port' => env('REDIS_SESSION_PORT', '6379'),
            //database set to 0 since only database 0 is supported in redis cluster
            'database' => env('REDIS_SESSION_DB', 0),
            //redis key prefix for this connection
            'prefix' => 's:',
        ],

        //connection used by the queue when redis cache is configured in config/queue.php
        'queue_app' => [
            'url' => env('REDIS_QUEUE_URL'),
            'host' => env('REDIS_QUEUE_HOST', '127.0.0.1'),
            'password' => env('REDIS_QUEUE_PASSWORD'),
            'port' => env('REDIS_QUEUE_PORT', '6379'),
            //database set to 0 since only database 0 is supported in redis cluster
            'database' => env('REDIS_QUEUE_DB', 0),
            //redis key prefix for this connection
            'prefix' => 'q:'.env('QUEUE_PREFIX_VERSION', ''),
        ],

        //connection used by the queue when redis cache is configured in config/queue.php
        'queue_job' => [
            'url' => env('REDIS_QUEUE_URL'),
            'host' => env('REDIS_QUEUE_HOST', '127.0.0.1'),
            'password' => env('REDIS_QUEUE_PASSWORD'),
            'port' => env('REDIS_QUEUE_PORT', '6379'),
            //database set to 0 since only database 0 is supported in redis cluster
            'database' => env('REDIS_QUEUE_DB', 1),
            //redis key prefix for this connection
            'prefix' => 'q:'.env('QUEUE_PREFIX_VERSION', ''),
        ],
    ],

];

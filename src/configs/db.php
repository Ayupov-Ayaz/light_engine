<?php


return [
    'host'        => getenv('DB_HOST'),
    'database'    => getenv('DB_NAME'),
    'user'        => getenv('DB_USER'),
    'password'    => getenv('DB_PASSWORD'),
    'date_format' => 'd.m.Y',
    'charset'     => 'cp1251',
    'driver'      => 'sqlsrv'
];
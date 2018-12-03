<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 23.11.18
 * Time: 15:00
 */

return array(

    // Главная
    '' => [
      'controller' => 'main',
      'action'     => 'index',
      'method'     => 'get',
      'access'     => [
          'all'
      ]
    ],

    // Авторизация
    'login/in' => [
        'controller' => 'login',
        'action'     => 'in',
        'method'     => 'get',
        'access'     => [
            'gust'
        ]
    ],

);
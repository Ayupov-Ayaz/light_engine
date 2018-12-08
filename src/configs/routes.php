<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 23.11.18
 * Time: 15:00
 */

return array(

    // Главная
    'main' => [
      'route'      => '',
      'controller' => 'main',
      'action'     => 'index',
      'method'     => 'get',
      'access'     => [
          'authorize'
      ]
    ],

    // Авторизация показать форму
    'show_authorization_page' => [
        'route'      => 'login/in',
        'controller' => 'login',
        'action'     => 'in',
        'method'     => 'get',
        'access'     => [
            'gust'
        ]
    ],

    // Авторизация
    'authorization' => [
        'route'      => 'login/auth',
        'controller' => 'login',
        'action'     => 'auth',
        'method'     => 'post',
        'access'     => [
            'gust'
        ]
    ]
);
<?php

return [

    /********************************************
     ***           Site configs               ***
     ********************************************/
    'system' => [
        //
    ],

    /********************************************
     ***         Site routing configs         ***
     ********************************************/
    'routes' => require 'routes.php',

    /********************************************
     ***          Database configs            ***
     ********************************************/
    'db' => require 'db.php',

    /********************************************
     ***          Views configs               ***
     ********************************************/
    'views' => [

        'views_path' => dirname(__DIR__) . '/views',
        /* имя шаблона используется по умолчанию */
        'layout_name' => 'default.php'
    ],

    /********************************************
     ***          Model configs               ***
     ********************************************/
    'models' => [
        'namespace' => 'App\Models'
    ],

    /********************************************
     ***       Authorization configs          ***
     ********************************************/
    'auth' => [
        /****************************************************************************************
         ***  Используется для авторизации/регистрации. Добавляется в конце hash пароля.        *
         ***  Нельзя терять этот ключ и менять его, если есть зарегестрированные пользователи!  *
         ***************************************************************************************/
        'encryption_key' => getenv('ENCRYPTION_KEY')
    ],
];
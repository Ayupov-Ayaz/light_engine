<?php

return [
  'path' => [
      'views' => dirname(__DIR__) . '/views'
  ],

  'routes' => require 'routes.php',
  'db'   => require 'db.php',
  'views' => [
      // имя шаблона используется по умолчанию
      'layout_name' => 'default.php'
  ]
];
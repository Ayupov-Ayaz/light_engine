<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 01.12.18
 * Time: 12:53
 */

namespace App\lib\db\impl;


use App\lib\db\iSingletonDbConnection;
use PDO;

class DbConnection implements iSingletonDbConnection
{
    /**
     * @var bool
     */
    private static $instance;

    /**
     * @var PDO
     */
    private $connection;

    /**
     * Создание класса через статичный метод getInstance()!
     * DB constructor.
     */
    private function __construct()
    {
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $configs = get_configs('db');
        $this->connection = new PDO($configs['driver'] . ':Server=' . $configs['host'] . ';Database=' . $configs['database'] ,
             $configs['user'], $configs['password'], $options);
    }

    /**
     * Singleton
     * @return DbConnection
     */
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Получить класс PDO подключенный к бд
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
}
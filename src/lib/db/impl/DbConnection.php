<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 01.12.18
 * Time: 12:53
 */

namespace App\lib\db\impl;


use App\lib\db\iSingleton;
use PDO;
class DbConnection implements iSingleton
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
     * @var string Database driver
     */
    private static $driver;
    /**
     * @var string host to Database
     */
    private static $host;
    /**
     * @var string | integer port to Database. Don't using if self::$docker_link_connection = true
     */
    private static $port;
    /**
     * @var string Database name
     */
    private static $database;
    /**
     * @var string Database username for authorization
     */
    private static $user;
    /**
     * @var string Database password for authorization
     */
    private static $password;
    /**
     * @var array connection or connection params errors
     */
    private static $errors;
    /**
     * @var bool used link to db service in docker-compose.yml file. if this param true then we don't using self::$port
     * for Database connection
     */
    private static $docker_link_connection = false;
    /**
     * @var string Database charset
     */
    private static $charset = 'utf8';
    /**
     * @var string Database date format
     */
    private static $date_format = 'd.m.Y';

    /**
     * Создание класса через статичный метод getInstance()!
     * DB constructor.
     */
    private function __construct()
    {
        switch (self::$driver) {
            case 'sqlsrv':
                $dsn = self::$driver . ':Server=' . self::$host;
                if(self::$docker_link_connection) { $dsn .= ',' . self::$port; }
                 $dsn .= ';Database=' . self::$database;
                break;

            case 'mysql':
                $dsn = self::$driver . ':host=' . self::$host;
                if(self::$docker_link_connection) { $dsn .= ';port=' . self::$port; }
                $dsn .= ';dbname=' . self::$database . ';charset=' . $this->charset;
                $options [PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                break;

            default:
                self::$errors[] = 'Не найден подходящий database driver! В файле .env указан "' . getenv('DB_DRIVER') .'"';
                $dsn = null;
                return;
        }
        // общие для всех параметры
        $options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        $options[PDO::ATTR_EMULATE_PREPARES] = false;
        $this->connection = new PDO($dsn, self::$user, self::$password, $options);
    }

    /**
     * Singleton
     * @return DbConnection
     */
    public static function getInstance() {
        if(!self::$instance) {
            self::setDatabaseParams();
            self::$instance = new self();
            if(!empty(self::$errors)) {
                foreach(self::$errors as $error) {
                    echo $error . "<br>";
                }
                exit();
            }
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

    private static function setDatabaseParams() {
        switch (strtolower(getenv('DB_DRIVER'))) {
            /**     MYSQL    **/
            case 'mysql':
                self::$driver   = 'mysql';
                self::$host     = getenv('MYSQL_HOST');
                self::$database = getenv('MYSQL_DATABASE');
                self::$port     = getenv('MYSQL_PORT');
                self::$user     = getenv('MYSQL_USER');
                self::$password = getenv('MYSQL_PASSWORD');
                break;

            /**     MSSQL    **/
            case 'mssql' :
            case 'sqlsrv' :
                self::$driver   = 'sqlsrv';
                self::$host     = getenv('MSSQL_HOST');
                self::$database = getenv('MSSQL_DATABASE');
                self::$port     = getenv('MSSQL_PORT');
                self::$user     = getenv('MSSQL_USER');
                self::$password = getenv('MSSQL_PASSWORD');
            break;
        }

        if(getenv('USING_DOCKER_SERVICE_LINK')) {
            self::$docker_link_connection = true;
        }
    }

}
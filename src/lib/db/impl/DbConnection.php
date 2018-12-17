<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 01.12.18
 * Time: 12:53
 */

namespace App\lib\db\impl;


use App\lib\db\iSingleton;
use App\lib\Validator;
use Exception;
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
     * @var string
     */
    private static $dsn;
    /**
     * @var array
     */
    private static $options;
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
        try{
            $this->connection = new PDO(self::$dsn, self::$user, self::$password, self::$options);
        } catch (Exception $e) {
            dd($e->getMessage() .':'. self::$dsn);
        }
    }

    /**
     * Singleton
     * @return DbConnection
     */
    public static function getInstance() {
        if(!self::$instance) {
            $showErrors = function($errors) {
                foreach($errors as $error) {
                    echo $error . "<br>";
                }
                exit();
            };
            self::setDatabaseParams();
            // ошибки на уровке текущего класса
            if(!empty(self::$errors)) {
                $showErrors(self::$errors);
            }
            // ошибки на уровне валидатора
            if(!self::checkConnectionParams()) {
                $showErrors(Validator::getErrors());
            }

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

    private static function setDatabaseParams() {

        if(getenv('USING_DOCKER_SERVICE_LINK')) {
            self::$docker_link_connection = true;
        }

        switch (strtolower(getenv('DB_DRIVER'))) {
            /**     MYSQL    **/
            case 'mysql':
                self::$driver   = 'mysql';
                self::$host     = getenv('MYSQL_HOST');
                self::$database = getenv('MYSQL_DATABASE');
                self::$port     = getenv('MYSQL_PORT');
                self::$user     = getenv('MYSQL_USER');
                self::$password = getenv('MYSQL_PASSWORD');
                //dsn
                self::$dsn = self::$driver . ':host=' . self::$host;
                if(!self::$docker_link_connection) { self::$dsn .= ';port=' . self::$port; }
                self::$dsn .= ';dbname=' . self::$database . ';charset=' . self::$charset;
                //options
                self::$options [PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
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
                //dsn
                self::$dsn = self::$driver . ':Server=' . self::$host;
                if(!self::$docker_link_connection) { self::$dsn .= ',' . self::$port; }
                self::$dsn .= ';Database=' . self::$database;
                break;
            default:
                self::$errors[] = 'Не найден подходящий database driver! В файле .env указан "' . getenv('DB_DRIVER') .'"';
                return;
        }
        // общие для всех параметры
        self::$options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        self::$options[PDO::ATTR_EMULATE_PREPARES] = false;
    }

    private static function checkConnectionParams() {
        Validator::validate(self::$user,'username', 'required');
        Validator::validate(self::$password,'password', 'required');
        Validator::validate(self::$host, 'host','required');
        Validator::validate(self::$database, 'database','required');
        if(Validator::hasErrors()) {
            return false;
        }
        return true;
    }

}
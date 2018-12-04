<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 01.12.18
 * Time: 23:11
 */

namespace App\lib\db\impl;

use PDO;

class DbQueries
{
    /**
     * @var PDO object - имеет подключение к бд
     */
    protected $pdo;

    protected $queryBuilder;

    /**
     * DbQueries constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->pdo = $dbConnection->getConnection();
        $this->queryBuilder = QueryBuilder::getInstance();
    }

    /**
     * Получить массив результатов
     * @param $query
     * @return array
     */
    public function queryArray($query){
        $stm = $this->query($query);
        return $stm->fetchAll();
    }

    /**
     * Получить строку результата
     * @param string $table - запрос будет адресован этой таблице
     * @param string | array $where - необходимо передавать в формате id = :id
     * @param array $params - параметры для $where и $columns
     * @return array
     */
    public function queryRow($table, $where, $params = []){
        $query = $this->queryBuilder->select($table)->where($where)->build();
        $stm = $this->query($query, $params);
        return $stm->fetch();
    }

    /**
     * Получить несколько полей в качестве результата запроса.
     * @param $table string
     * @param $columns array
     * @param $where string
     * @param array $params
     * @return mixed
     */
    public function queryGetColumns($table, $columns, $where, $params = []) {
        $query = $this->queryBuilder->select($table, $columns)->where($where)->build();
        $stm = $this->query($query, $params);
        return $stm->fetch();
    }

    /**
     * Отправляет запрос на получение колонки из таблицы
     * @param string $table - запрос будет адресован этой таблице
     * @param string | array $columns
     * @param string | array $where - необходимо передавать в формате id = :id
     * @param array $params - параметры для $where и $columns
     * @return array | mixed
     */
    public function queryGetValue($table, $columns, $where, $params = []) {
        $this->queryBuilder->select($table, $columns)->where($where);
        $query = $this->queryBuilder->build();
        dd($query);
        $stm =  $this->query($query, $params);
        if(empty($where)) {
           return $stm->fetchAll(PDO::FETCH_COLUMN);
        }
        return $stm->fetchColumn();
    }


    /**
     * Обновление.
     * @param string $table - запрос будет адресован этой таблице
     * @param string | array $columns
     * @param string | array $where - необходимо передавать в формате id = :id
     * @param array $params - параметры для $where и $columns
     * @return int - сколько строк обновлено
     */
    public function update($table, $columns, $where, $params) {
        $query = $this->queryBuilder->update($table,$columns)->where($where)->build();
        $stm = $this->query($query, $params);
        return $stm->rowCount();
    }

    /**
     * Удаление
     * @param string $table - запрос будет адресован этой таблице
     * @param string | array $where - необходимо передавать в формате id = :id
     * @param array $params - параметры для $where и $columns
     * @return int - сколько строк обновлено
     */
    public function delete($table, $where, $params){
        $query = $this->queryBuilder->delete($table)->where($where)->build();
        $stm = $this->query($query, $params);
        return $stm->rowCount();
    }

    /**
     * Обертка над функцие PDO::query(). Получает результат лишь по ключам
     * @param $query
     * @param array $params
     * @return false| \PDOStatement
     */
    public function query($query, $params = []) {
        $stm = $this->pdo->prepare($query);
        $params = $this->clearParams($params);
        if(!empty($params)) {
            $stm->execute($params);
        } else {
            $stm->execute();
        }
        return $stm;
    }

    /**
     * Обработка параметров
     * @param $params array
     * @return array
     */
    public function clearParams($params) {
        if(!is_array($params)) {
            return [];
        }
        foreach ($params as $key => $val) {
            $params[$key] = trim(htmlspecialchars($val));
        }
        return $params;
    }
}
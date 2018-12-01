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

    /**
     * DbQueries constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->pdo = $dbConnection->getConnection();
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
     * @param $table - таблица
     * @param $where
     * @param array $params
     * @return mixed
     */
    public function queryRow($table, $where, $params = []){
        $query = $this->buildQuery('select', $table, $where);
        $stm = $this->query($query, $params);
        return $stm->fetch();
    }

    /**
     * Отправляет запрос на получение колонки из таблицы
     * @param $table
     * @param $column
     * @param $where
     * @return string | bool
     */
    public function queryGetValue($table, $column, $where, $params = []) {
        $query = $this->buildQuery('select', $table, $where, $column);
        $stm =  $this->query($query, $params);
        return $stm->fetchColumn();
    }

    /**
     * Обертка над функцие PDO::query(). Получает результат лишь по ключам
     * @param $query
     * @param array $params
     * @return false| \PDOStatement
     */
    private function query($query, $params = []) {
        $stm = $this->pdo->prepare($query);
        if(!empty($params)) {
            $stm->execute($params);
        } else {
            $stm->execute();
        }
        return $stm;
    }

    /**
     * Построитель запросов
     * @param $table
     * @param string $columns
     * @param $where
     * @return string
     */
    private function buildQuery($operation, $table, $where = null, $columns = '*') {
        $sql = '';
        switch ($operation) {
            case 'select' :
                $sql = $operation . ' ' . $columns . ' from ' . $table . (isset($where) ? ' where ' . $where : '');
                break;
            case 'insert' :
                $sql = '';
                break;
            case 'update' :
                $sql = '';
                break;
            case 'delete' :
                $sql = '';
                break;
        }
        return $sql;
    }


}
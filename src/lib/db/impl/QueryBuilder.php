<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 02.12.18
 * Time: 11:41
 */

namespace App\lib\db\impl;

class QueryBuilder
{
    /**
     * @var QueryBuilder
     */
    private static $instance;

    /**
     * @var array Ошибки при формирования запроса
     */
    private $errors  = [];

    /**
     * @var string сам запрос
     */
    private $query = '';

    private $step = 0;

    /**
     * Защита от удаления без условия
     * @var bool
     */
    private $prepareToDelete = false;

    /**
     * Защита от обновления данных без условия
     * @var bool
     */
    private $prepareToUpdate = false;
    /**
     * QueryBuilder constructor.
     */
    private function __construct()
    {
    }

    /**
     * Создание экземпляра класса QueryBuilder
     * @return QueryBuilder
     */
    public static function getInstance(){
        self::$instance =  new self();
        return self::$instance;
    }

    /**
     * Операция с SELECT
     * @param $table
     * @param string | array $columns
     * @return QueryBuilder
     */
    public function select($table, $columns = '*') {
        $this->step++;
        if(is_array($columns)) {
            $columns = implode(', ', $columns);
        }

        $this->query = 'SELECT ' . $columns . ' FROM ' . $table;
        return self::$instance;
    }

    /**
     *
     * @param $table
     * @param $columns
     * @return QueryBuilder
     */
    public function update($table, $columns) {
        $this->step++;
        $this->prepareToUpdate = true;
        if(is_array($columns)) {
            $params = [];
            foreach ($columns as $key => $val) {
                $params[] = $key . '=' . $val;
            }
            $columns = implode(',', $params);
        }

        $this->query = 'UPDATE ' . $table . ' SET ' . $columns;
        return self::$instance;
    }

    /**
     *
     * @param $table
     * @return QueryBuilder
     */
    public function delete($table) {
        $this->step++;
        $this->prepareToDelete = true;
        $this->query = 'DELETE FROM ' . $table;
        return self::$instance;
    }

    /**
     * @param $where
     * @param string $operation
     * @return QueryBuilder
     */
    public function where($where, $operation = '=') {
        if(empty($where)) {
            return self::$instance;
        }
        $this->step++;
        if(empty($this->query)) {
            $this->errors[] = "На шаге ". $this->step .", для 'where' произошла ошибка: Начальный запрос пуст!";
            return self::$instance;
        }
        if(is_array($where)) {
            $_where = [];
            foreach ($where as $key => $val) {
                $_where[] = $key . $operation . $val;
            }
            $where = implode(' AND ', $_where);
        }
        $this->query .= ' WHERE ' . $where;
        // отключаем защиту от удаления без условия
        if($this->prepareToDelete) $this->prepareToDelete = false;
        // отключаем защиту от обновления без условия
        if($this->prepareToUpdate) $this->prepareToUpdate = false;
        return self::$instance;
    }

    /**
     * @return array | string
     */
    public function build() {
        if(!empty($this->errors)) {
            return $this->errors;
        }

        if($this->prepareToDelete) {
            return 'Запрещено удаление без условия!';
        }
        if($this->prepareToUpdate) {
            return 'Запрещено обновление без условия!';
        }

        $query = $this->query;
        $this->clear();
        return $query;
    }

    /**
     * Затирает все данные
     * @return void
     */
    public function clear() {
        $this->step = 0;
        $this->query = '';
        $this->errors = [];
        $this->prepareToUpdate = false;
        $this->prepareToDelete = false;
    }
}
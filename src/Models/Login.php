<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 03.12.18
 * Time: 22:06
 */

namespace App\Models;


use App\Core\Model;

class Login extends Model
{
    /**
     * @var string Таблица для авторизации/регистрации пользователей
     */
    private $table;

    /**
     * @var array
     */
    private $errors;
    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $authConfigs = get_configs('auth');
        if(empty($authConfigs['table'])) {
           $this->errors[] = ['status' => 500, 'message' => 'Не задана таблица для авторизации!'];
        }else{
            $this->table = $authConfigs['table'];
        }
    }

    /**
     * Получить данные для проверки авторизации по логину пользователя
     * @param $login
     * @return array/null
     */
    public function getDataForAuthorization($login){
        if(!empty($this->errors)) {
            return $this->errors;
        }
       return $this->dbQueries->queryGetColumns($this->table,['login', 'password'],
           'login = :login', ['login' => $login] );
    }


}
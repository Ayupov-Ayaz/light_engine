<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 03.12.18
 * Time: 19:43
 */

namespace App\Acl;


class AccessControl
{
    /**
     * @var array
     */
    protected $access;

    /**
     * AccessControl constructor.
     * @param $routeParams
     */
    public function __construct($routeParams)
    {
        if(isset($routeParams['access'])) {
            $this->access =  $routeParams['access'];
        } else {
            $this->access = ['gust'];
        }
    }

    /**
     * Проверка доступа на контроллер
     * @return bool
     */
    public function checkAccess(){
        if(in_array('all', $this->access)) return true;
        elseif(in_array('gust', $this->access) && !$this->authorized()) return true;
        elseif ($this->authorized() && in_array('authorize', $this->access)) return true;
        elseif ($this->isAdmin() && in_array('admin', $this->access)) return true;
        else return false;
    }

    /**
     * Проверка на то, что это администратор
     * TODO: Реализовать
     * @return bool
     */
    public function isAdmin() {
        return false;
    }

    /**
     * Проверка на то, что пользователь авторизован
     * @return bool
     */
    public function authorized(){
        if(isset($_SESSION['authorization']['login']) && isset($_SESSION['authorization']['id'])) {
            return true;
        }
        return false;
    }


}
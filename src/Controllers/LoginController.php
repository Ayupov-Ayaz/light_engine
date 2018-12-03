<?php
/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 01.12.18
 * Time: 10:06
 */

namespace App\Controllers;


use App\Core\Controller;

class LoginController extends Controller
{

    public function inAction(){
        // TODO: old data
        $this->view->setLayout('login');
        $this->view->render('Авторизация');
    }

    public function authAction(){
        $login = isset($_POST['login']) ? trim(htmlspecialchars($_POST['login'])) : null;
        $password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])) : null;
        if(empty($login) || empty($password)) {
            $this->view->response(400, 'Поля логин и пароль обязательны для заполнения!!!!');
            exit();
        }
        //получаем пользователя по login
        $auth_conf = get_configs('auth');
        $auth_data = $this->model->getDataForAuthorization($login);

        // моделька вернула ошибку
        if(isset($auth_data['error'])) {
            $this->view->response($auth_data);
            exit();
        }

        // такого пользователя нет
        if(!isset($auth_data['login'])) {
            $this->view->response(400, 'Пользователь не найден');
            exit();
        }

        $password = md5($password . $auth_conf['encryption_key']);
        // пароли не совпали
        if(! $password == $auth_data['password']) {
            $this->view->response(400, 'Не правильно введен пароль. Попробуйте снова.');
            exit();
        }
        // записываем в сессию
        $this->prepareUser($auth_data);
        redirect('/');
    }

    private function prepareUser($auth_data){
        $_SESSION['authorization']['login'] = $auth_data['login'];
        $_SESSION['authorization']['id'] = $auth_data['id'];
    }


}
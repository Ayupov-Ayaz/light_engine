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
        $this->view->render('Авторизация');
    }
}
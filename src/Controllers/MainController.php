<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 23.11.18
 * Time: 16:21
 */

namespace App\Controllers;
use App\Core\Controller;

class MainController extends Controller
{

    public function indexAction() {
        $this->view->render('Главная страница');
    }
}
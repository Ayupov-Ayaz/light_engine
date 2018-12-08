<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 23.11.18
 * Time: 14:45
 */

namespace App\Core;
use App\Acl\AccessControl;

require dirname(__DIR__) .'/helpers/url_helper.php';

class Router {
    /**
     * Массив маршрутов
     * @var array
     */
    protected $routes = [];

    /**
     * Параметры маршрута
     * @var array
     */
    protected $params = [];

    /**
     * @var AccessControl
     */
    protected $accessControl;
    /**
     * Router constructor.
     */
    public function __construct() {
        $routes_array     = require dirname(__DIR__). '/configs/routes.php';

        foreach($routes_array as $route => $params) {
            $this->add($route, $params);
        }

    }

    /**
     * Добавление маршрутов
     * @param $route
     * @param $params
     */
    protected function add($route, $params) {
        // сохраняем как регулярку
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    /**
     * Проверить на существование маршрута
     * Если маршрут существует, то заполняются параметры по этому маршруту
     * @return bool
     */
    public function match() {
        $url = current_url();

        $url = trim($url, '/');
        foreach ($this->routes as $route => $params) {
            if(preg_match($route, $url)) {
                if($_SERVER['REQUEST_METHOD'] != strtoupper($params['method'])) {
                    return false;
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Запустить маршрут
     * @return void
     */
    public function run() {
        // ищем наши маршруты
        if($this->match()) {

            // ищем controller
            $controller_path = 'App\\Controllers\\' . ucfirst($this->params['controller']). 'Controller';
            if(!class_exists($controller_path)) {
                View::errorCode(404);
            }

            // ищем action
            $action = $this->params['action'] . 'Action';
            if(!method_exists($controller_path, $action)) {
                View::errorCode(404);
            }

            // проверяем доступ пользователя к этому маршруту
            $this->accessControl = new AccessControl($this->params);
            if(!$this->accessControl->checkAccess()) {
                if(in_array('authorize', $this->accessControl->getRouteAccess())) {
                    redirect('/login/in');
                    exit();
                }
                View::errorCode(403);
            }
            $controller = new $controller_path($this->params);
            $controller->$action();

        } else {
            View::errorCode(404);
        }
    }
}
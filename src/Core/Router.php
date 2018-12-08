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

        foreach($routes_array as $routeName => $params) {
            $this->add($routeName, $params);
        }

    }

    /**
     * Добавление маршрутов
     * @param $routeName string
     * @param $params array
     */
    protected function add($routeName, $params) {
        // сохраняем как регулярку
        $params['reg_route'] = '#^' . $params['route'] . '$#';
        $this->routes[$routeName] = $params;
    }

    /**
     * Проверить на существование маршрута
     * Если маршрут существует, то заполняются параметры по этому маршруту
     * @return bool
     */
    public function match() {
        $url = trim(current_url(), '/');
        foreach ($this->routes as $params) {
            if(preg_match($params['reg_route'], $url)) {
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
                    $url = base_url($this->routes['show_authorization_page']['route']);
                    redirect($url);
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
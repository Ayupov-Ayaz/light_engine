<?php
namespace App\Core;

use App\Acl\AccessControl;

abstract class Controller {

    /**
     * @var array
     */
    protected $route;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var AccessControl
     */
    protected $acl;
    /**
     * Controller constructor.
     * @param $route
     */
    public function __construct($route) {
        $this->acl = new AccessControl($route);
        $this->route = $route;
        $this->view = new View($route);
        $this->model = $this->loadModel($route['controller']);
    }

    /**
     * Получить все маршруты
     * @return array
     */
    public function getRoute() {
        return $this->route;
    }

    protected function loadModel($name) {
        $config = get_configs('models');
        $model_path = $config['namespace'] . '\\' . ucfirst($name);
        if(class_exists($model_path)) {
            return new $model_path();
        }
    }
}
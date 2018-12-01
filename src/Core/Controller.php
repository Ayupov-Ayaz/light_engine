<?php
namespace App\Core;

abstract class Controller {

    /**
     * @var array
     */
    protected $route;

    /**
     * @var string View
     */
    protected $view;

    /**
     * Controller constructor.
     * @param $route
     */
    public function __construct($route) {
        $this->route = $route;
        $this->view = new View($route);
    }

    /**
     * Получить все маршруты
     * @return array
     */
    public function getRoute() {
        return $this->route;
    }
}
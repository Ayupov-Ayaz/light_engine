<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 24.11.18
 * Time: 22:54
 */

namespace App\Core;
require dirname(__DIR__) .'/helpers/base_helper.php';

class View {
    /**
     * Путь до view
     * @var string
     */
    private $path;

    /**
     * Шаблон
     * @var string
     */
    private $layout;

    /**
     * View constructor.
     * @param $route
     */
    public function __construct($route) {
        $views_config = get_configs('views');
        // Шаблон по умолчанию
        $this->layout = $views_config['layout_name'];
        $this->path = $route['controller'] . '/' . $route['action'] . '.php';
    }

    /**
     * Сборка view
     * @param $title - загаловок
     * @param array $vars - параметры для отображения во view
     */
    public function render($title, $vars = []) {
        if(!file_exists(view_path($this->path))) {
            echo "View not found: <b>" . view_path($this->path) . "<b>!";
            exit;
        }

        if(!file_exists(layout_path( $this->layout))) {
            echo "View not found: <b>" . layout_path( $this->layout) . "<b>!";
            exit;
        }
        // распаковываем наш массив в переменные
        extract($vars);
        // буферизируем наш контент
        ob_start();
        require view_path($this->path);
        $content = ob_get_clean();

        //подключаем шаблон
        require layout_path( $this->layout);
    }

    /**
     * Подключения view для страниц с ошибками
     * @param $code - http код
     */
    public static function errorCode($code) {
        http_response_code($code);
        $title = 'Страница не найдена';
        $views_configs = get_configs('views');
        require  layout_path($views_configs['layout_name']);
        $path = view_path('errors/' . $code . '.php');
        if(!file_exists($path)) {
            $title = 'Непредвиденная ошибка!';
            exit;
        }
        require  view_path('errors/' . $code . '.php');
        exit;
    }

}
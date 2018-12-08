<?php

if(! function_exists('base_url')) {
    function base_url($url = '') {
        return sprintf(
            "%s://%s:%d/%s/",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $url
        );
    }
}

if(! function_exists('current_url')) {
    /**
     * Возвращает текущий URI
     * @param bool $with_method - вернуть ли в виде массива с методом
     * @return string / array ['uri', 'method']
     */
    function current_url($with_method = false) {

        $route = [
            'uri'    => $_SERVER['REQUEST_URI'],
            'method' => $_SERVER['REQUEST_METHOD']
        ];
        if($with_method) {
            return $route;
        }
        return $route['uri'];
    }
}

if(! function_exists('redirect')) {
    /**
     * Функция переадресации
     * @param $url - куда переадресовать
     */
    function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}
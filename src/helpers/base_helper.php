<?php

if(!function_exists('get_configs')) {
    /**
     * Получить конфиги сайта, если передан ключ, то получит не все конфиги, а лишь необходимый, если он имеется,
     * если по ключу не будет найден элемент, то функция вернет весь массив
     * @param bool $key
     * @return array - массив конфигураций сайта
     */
    function get_configs($key = false) {
        $configs = require dirname(__DIR__) . '/configs/web.php';
        if($key && isset($configs[$key])) {
            return $configs[$key];
        }
        return $configs;
    }
}

if(!function_exists('view_path')) {
    /**
     * Получить директорию где хранятся все виды
     * @param string $sub_folder - продолжение пути, должен заканчиваться на имя файла с расришением
     * @return string
     */
    function view_path(string $sub_folder = '') {
        $url_configs = get_configs('path');
        return $url_configs['views'] . '/' . $sub_folder;
    }
}

if(!function_exists('layout_path')) {
    /**
     * Получить путь до директории где хранятся шаблоны построения видов
     * @param string $path - продолжение пути, должен заканчиваться на полное имя класса с расширением
     * @return string
     */
    function layout_path(string $path) {
        return view_path('layouts/' . $path);
    }
}

if(!function_exists('dd')) {
    /**
     * Dump and Die - аналог var_dump() + die() только с нормальным выводом
     * @param $obj
     * @param bool $alive
     */
    function dd($obj, bool $alive = false) {
        echo "<hr><pre>";
        $debug = debug_backtrace();
        echo "Имя файл : <b>" . $debug[0]['file'] . "</b><br>";
        echo "Функция  : <b>" . $debug[0]['function'] . "()</b><br>";
        echo "Строка   : <b>" . $debug[0]['line'] . "()</b><br><br><br>";
        var_dump($obj);
        if(!$alive) {
            exit();
        }
        echo "</pre><br>";
    }
}
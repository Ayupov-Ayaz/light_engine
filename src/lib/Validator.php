<?php
/**
 * Created by PhpStorm.
 * User: Ayaz Ayupov
 * Date: 16.12.18
 * Time: 21:24
 */

namespace App\lib;

use App\Exceptions\ValidateException;

class Validator
{
    /**
     * @var array массив с ошибками
     */
    private static $errors;

    /**
     * @var string тип проверяемой переменной
     */
    private static $var_type;

    /**
     * @param $var
     * @param $options string | array
     * @param string $separator
     * @return bool
     * @throws ValidateException
     */
    public static function validate($var, $options, $separator = '|') {
        if(is_string($options)) {
            $options = explode($separator, $options);
        }

        $throw_error = function ($message) {
            echo $message;
            exit();
        };

        self::$var_type = null;
        self::$errors = [];
        foreach ($options as $option) {
            switch ($option):
                // Обязательно заполнено
                case 'required':
                    if(empty($var)) {
                        self::$errors[] = 'Не заполнена!';
                    }
                    break;

                // Должна быть строкой
                case 'string':
                case 'str':
                    self::$var_type = 'str';
                    if(!is_string($var)) {
                        self::$errors[] = 'Не являетится строкой!';
                    }
                    break;

                // Должна быть числом
                case 'number':
                case 'integer':
                case 'int':
                    if(self::$var_type == 'str') {
                        $throw_error('Нельзя указывать несколько типов для проверки!');
                    }
                    self::$var_type = 'int';
                    if(!is_integer($var)) {
                        self::$errors[] = 'Не является числом!';
                    }
                    break;

                // Проверка на минимум
                case  false !== strpos($option, 'min') :
                    $number = self::getIntInParam($option);
                    if(self::$var_type === 'int') {
                        if(! (int)$var >= $number) {
                            self::$errors[] = 'Число должно быть больше "' . $number . '"';
                        }
                    } elseif (self::$var_type === 'str') {
                        if(!self::min($var, $number)) {
                            self::$errors[] = 'Строка должна быть не меньше ' . $number;
                        }
                    } else {
                        $throw_error('Для проверки на min нужно указать тип!');
                    }
                    break;

                // Проверка на максимум
                case false !== strpos($option, 'max') :
                    $number = self::getIntInParam($option);
                    if(self::$var_type === 'int') {
                        if(! (int)$var <= $number) {
                            self::$errors[] = 'Число должно быть меньше ' . $number;
                        }
                    } elseif (self::$var_type === 'str') {
                        if(!self::max($var, $number)) {

                            self::$errors[] = ' Строка должна быть не больше ' . $number;
                        }
                    } else {
                       echo 'Для проверки на max нужно указать тип!'; exit();
                    }

                    break;

                default:
                    $throw_error('Неизвестная операция для проверки - "' . $option .'"');
            endswitch;
        }
        return empty(self::$errors) ? true : false;
    }

    /**
     * @param $var
     * @param $length
     * @return bool
     */
    private static function max($var, $length) {
        return iconv_strlen($var, 'UTF8') <= $length;
    }

    /**
     * @param $var
     * @param $length
     * @return bool
     */
    private static function min($var, $length) {
        return $length >= iconv_strlen($var, 'UTF8');
    }

    private static function getIntInParam($string, $separator = '=') {
        return (int) mb_strcut($string, strpos($string,$separator) + 1);
    }

    public static function getErrors() {
        return self::$errors;
    }

    public static function hasErrors() {
        return !empty(self::$errors);
    }

}
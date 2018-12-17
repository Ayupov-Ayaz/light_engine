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
     * @param $paramName
     * @param $options string | array
     * @param string $separator
     * @return bool
     */
    public static function validate($var, $paramName, $options, $separator = '|') {
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
                        self::$errors[] = $paramName . ' - не заполнена!';
                    }
                    break;

                // Должна быть строкой
                case 'string':
                case 'str':
                    self::$var_type = 'str';
                    if(!is_string($var)) {
                        self::$errors[] = $paramName . '- не являетится строкой!';
                    }
                    break;

                // Должна быть числом
                case 'number':
                case 'integer':
                case 'int':
                    if(self::$var_type == 'str') {
                        $throw_error("Для $paramName Нельзя указывать несколько типов для проверки!");
                    }
                    self::$var_type = 'int';
                    if(!is_integer($var)) {
                        self::$errors[] = '$paramName - не является числом!';
                    }
                    break;

                // Проверка на минимум
                case  false !== strpos($option, 'min') :
                    $number = self::getIntInParam($option);
                    if(self::$var_type === 'int') {
                        if(! (int)$var >= $number) {
                            self::$errors[] = "$paramName должнен быть больше чем \"$number\" ";
                        }
                    } else {
                        if(! self::min($var, $number)) {
                            self::$errors[] = "Строка $paramName должна быть не меньше \"$number\" символов, а у вас ";
                        }
                    }
                    break;

                // Проверка на максимум
                case false !== strpos($option, 'max') :
                    $number = self::getIntInParam($option);
                    if(self::$var_type === 'int') {
                        if(! (int)$var <= $number) {
                            self::$errors[] = "$paramName должен быть меньше $number";
                        }
                    } elseif (self::$var_type === 'str') {
                        if(!self::max($var, $number)) {

                            self::$errors[] = "Строка $paramName должна быть не больше $number";
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
        return iconv_strlen($var, 'UTF8') >= $length  ;
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
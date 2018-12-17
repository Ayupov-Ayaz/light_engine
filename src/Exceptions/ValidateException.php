<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 16.12.18
 * Time: 22:47
 */

namespace App\Exceptions;


use Exception;

class ValidateException extends Exception
{
    public function __construct($message) {
        parent::__construct($message);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 01.12.18
 * Time: 14:14
 */

namespace App\lib\db;

interface iSingleton
{
    /**
     * Singleton connection to database
     * @return mixed
     */
    public static function getInstance();
}
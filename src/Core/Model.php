<?php
/**
 * Created by PhpStorm.
 * User: Ayupov Ayaz
 * Date: 23.11.18
 * Time: 14:50
 */

namespace App\Core;


use App\lib\db\impl\DbConnection;
use App\lib\db\impl\DbQueries;

abstract class Model {

    protected $dbQueries;

    /**
     * Model constructor.
     */
    public function __construct() {
        $db_connection = DbConnection::getInstance();
        $this->dbQueries = new DbQueries($db_connection);
    }
}
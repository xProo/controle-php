<?php

namespace App\Database;

use App\Database\Dsn;


class DbConnexion
{

    public function execute()
    {
        try {
            $dsn = new Dsn();
            $db = new \PDO("mysql:host={$dsn->getHost()};dbname={$dsn->getDbName()};port={$dsn->getPort()}", $dsn->getUser(), $dsn->getPassword());
            return $db;
        } catch (\PDOException $e) {
            echo $e->getMessage();

            $db = null;
            return false;
        }
    }
}

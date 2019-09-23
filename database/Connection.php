<?php

class Connection
{
    public static function make( $configDB )
    {
        $pdo = new PDO(
            "{$configDB['connection']}; dbname={$configDB['database']}; charset={$configDB['charset']}; ",
            "{$configDB['username']}",
            "{$configDB['password']}" );
        return $pdo;
    }
}
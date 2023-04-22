<?php


class UbDbUtil {
	/** @return PDO */

    static function getPDO(): PDO
    {
        $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE, DB_USER, DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    static function query($sql, $params = array()): false|PDOStatement
    {
        $db = self::getPDO();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
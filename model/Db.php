<?php

class Db
{
    /**
     * @var PDO Database connection
     */
    private static PDO $connection;

    /**
     * @var array Default settings
     */
    private static array $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Connects to database using given params
     * @param string $host Host name
     * @param string $database Database name
     * @param string $user User name
     * @param string $password Password
     * @return bool True on success, else false
     */
    public static function connect(string $host, string $database, string $user, string $password) : bool
    {
        if (!isset(self::$connection))
        {
            try 
            {
                self::$connection = @new PDO (
                    "mysql:host=$host;dbname=$database",
                    $user,
                    $password,
                    self::$options
                );
            }
            catch (PDOException $e)
            {
                return false;
            }
            
            return true;
        }   
    }

    /**
     * Executes query and returns PDO statement
     * @param string $query Query
     * @param array $params Params array
     * @return mixed Returns PDOStatement, false when failure
     */
    private static function execute(string $query, array $params = array()) : PDOStatement|bool
    {
        $ret = self::$connection->prepare($query);
        $ret->execute($params);
        return $ret;
    }

    /**
     * Runs query and returns number of effected rows
     * @param string $query Query
     * @param array $params Params array
     * @return int Number of effected rows
     */
    public static function query(string $query, array $params = array()) : int
    {
        return self::execute($query, $params)->rowCount();
    }

    /**
     * Runs query and returns all effected rows
     * @param string $query Query
     * @param array $params Params array
     * @return mixed Returns results array or false when failure
     */
    public static function queryAll(string $query, array $params = array()) : array|bool
    {
        return self::execute($query, $params)->fetchAll();
    }

    /**
     * Runs query and returns first effected row
     * @param string $query Query
     * @param array $params Params array
     * @return mixed Result array or false when failure
     */
    public static function queryOne(string $query, array $params = array()) : array|bool
    {
        return self::execute($query, $params)->fetch();
    }

    /**
     * Runs query and returns first effected row's column
     * @param string $query Query
     * @param array $params Params array
     * @return string Value from first column from first effected row
     */
    public static function querySingle(string $query, array $params = array()) : string
    {
        $ret = self::queryOne($query, $params);
        return $ret[0];
    }

    /**
     * Inserts into database
     * @param string $table Name of table
     * @param array $data Asociative array, key == database column name
     * @return bool True on success, else false
     */
    public static function insert(string $table, array $data = array()) : bool
    {
        return self::query("
            INSERT INTO `$table` (`" . implode('`, `', array_keys($data)) . "`)
            VALUES (" . str_repeat('?,', sizeof($data) - 1) . "?)",
            array_values($data));
    }

    /**
     * Updates database
     * @param string $table Name of table
     * @param array $data Asociative array, key == database column name
     * @param string $condition SQL condition
     * @param array $params Condition params
     * @return bool True on succes, else false
     */
    public static function update(string $table, array $data = array(), string $condition, array $params = array()) : bool
    {
        return self::query("
            UPDATE `$table`
            SET `" . implode('` = ?, `', array_keys($data)) . "` = ? " . $condition,
            array_merge(array_values($data), array_values($params)));
    }

    /**
     * Returns last insert ID
     * @return int Last insert ID
     */
    public static function getLastId() : int
    {
        return self::$connection->lastInsertId();
    }
}
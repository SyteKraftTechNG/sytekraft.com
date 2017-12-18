<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 11/30/17
 * Time: 10:33 PM
 */
abstract class DataSource
{
    private static $query;
    private static $result;

    private static $queryLog;
    private static $lastId;

    private static $server = "localhost";
    private static $port = 3306;
    private static $user = "root";
    private static $password = "";
    private static $db = "newlanding";

    private static $dsn;
    private static $initialised = false;
    private static $errors;

    /**
     *
     */
    public static function init() {
        self::setDsn(self::MySqlDSN(self::$db, self::$server, self::$port));
        self::$errors = array();
        self::$initialised = true;
    }

    /**
     * @return PDO
     */
    public static function connect() {
        try {
            if (self::$initialised) {
                return new PDO(self::$dsn, self::$user, self::$password);
            } else {
                throw new PDOException();
            }
        } catch (PDOException $e) {
            self::setErrors($e->getMessage());
        }
    }

    /**
     * Defines a data source name for use with PDO for connection to databases.
     * @param string $db: Name of the database to be linked.
     * @param string $host: Name of host; usually localhost.
     * @param int $port: Port number; usually 3306 or 3310.
     * @return string
     */
    public static function MySqlDSN($db, $host="localhost", $port=null) {
        $server = $host. ((isset($port)) ? (":". $port) : "");
        $dsn = "mysql:host=". $server. ";dbname=". $db;
        return $dsn;
    }

    /**
     * @return mixed
     */
    public static function getQuery()
    {
        return self::$query;
    }

    /**
     * @param mixed $query
     */
    public static function setQuery($query)
    {
        self::$query = $query;
    }

    /**
     * @return mixed
     */
    public static function getResult()
    {
        return self::$result;
    }

    /**
     * @param mixed $result
     */
    public static function setResult($result)
    {
        self::$result = $result;
    }

    /**
     * @return mixed
     */
    public static function getQueryLog()
    {
        return self::$queryLog;
    }

    /**
     * @param mixed $queryLog
     */
    public static function setQueryLog($queryLog)
    {
        self::$queryLog = $queryLog;
    }

    /**
     * @return mixed
     */
    public static function getLastId()
    {
        return self::$lastId;
    }

    /**
     * @param mixed $lastId
     */
    public static function setLastId($lastId)
    {
        self::$lastId = $lastId;
    }

    /**
     * @return string
     */
    public static function getServer()
    {
        return self::$server;
    }

    /**
     * @param string $server
     */
    public static function setServer($server)
    {
        self::$server = $server;
    }

    /**
     * @return int
     */
    public static function getPort()
    {
        return self::$port;
    }

    /**
     * @param int $port
     */
    public static function setPort($port)
    {
        self::$port = $port;
    }

    /**
     * @return string
     */
    public static function getUser()
    {
        return self::$user;
    }

    /**
     * @param string $user
     */
    public static function setUser($user)
    {
        self::$user = $user;
    }

    /**
     * @return string
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * @param string $password
     */
    public static function setPassword($password)
    {
        self::$password = $password;
    }

    /**
     * @return string
     */
    public static function getDb()
    {
        return self::$db;
    }

    /**
     * @param string $db
     */
    public static function setDb($db)
    {
        self::$db = $db;
    }

    /**
     * @return mixed
     */
    public static function getDsn()
    {
        return self::$dsn;
    }

    /**
     * @param mixed $dsn
     */
    public static function setDsn($dsn)
    {
        self::$dsn = $dsn;
    }

    /**
     * @return boolean
     */
    public static function isInitialised()
    {
        return self::$initialised;
    }

    /**
     * @param boolean $initialised
     */
    public static function setInitialised($initialised)
    {
        self::$initialised = $initialised;
    }

    /**
     * @return mixed
     */
    public static function getErrors()
    {
        return self::$errors;
    }

    /**
     * @param mixed $errors
     */
    public static function setErrors($errors)
    {
        if (is_null(self::getErrors())) {
            self::$errors = (array) $errors;
        } else {
            if (is_array($errors)) {
                self::$errors = array_merge(self::getErrors(), $errors);
            } else {
                self::$errors[] = $errors;
            }
        }
    }

    public static function query($query, array $params = null) {
        $connect = self::connect();

        // get the first word of the query - let's call this the operative
        $words = explode(" ", $query);
        $operative = strtolower($words[0]);

        // this array contains operations that 'pull' data from the database
        $pullOps = array("select", "desc", "show");

        // this array contains bad operations
        $badOps = array("drop", "truncate");

        // if this is a bad operation,
        if (in_array($operative, $badOps)) {
            self::setErrors("This query seems bad...");
        } else {
            try {
                // set the query
                self::$query = $query;

                // prepare the query...
                $stmt = $connect->prepare($query);

                // ...and bind values parametrically
                if (isset($params) && !empty($params)) {
                    foreach($params as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                }
                self::logQuery($query, $params);

                // then, run the query.
                $go = $stmt->execute();
                if (!$go) throw new PDOException();

                // if the query is a "pull operation", process the results
                if (in_array($operative, $pullOps) || $operative == "insert") {
                    // capture the results
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    self::setResult($result);

                    if ($operative == "insert") {
                        // find the lastInsertId, and save that to the data object
                        $id = $connect->lastInsertId($stmt->queryString);
                        $id = ($id > 0) ? $id : 0;
                        self::setLastId($id);
                    }
                }

            } catch(PDOException $e) {
                self::setErrors($e->getMessage());
            }
        }
    }

    public static function logQuery($query, array $params = null) {
        $q = $query;
        if (!empty($params)) {
            foreach($params as $key => $value) {
                if (!isset($value)) {
                    $v = "NULL";
                } elseif (is_bool($value)) {
                    $v = ($value == true) ? "TRUE" : "FALSE";
                } elseif (is_float($value)) {
                    $v = floatval($value);
                } elseif (is_int($value)) {
                    $v = intval($value);
                } else {
                    $v = "'$value'";
                }
                $q = str_replace($key, $v, $q);
            }
            $query = $q;
        }
        self::$queryLog[] = $query;
    }


    public static function recordSet($query, $params = null, $rows = 10, $page = 1) {
        // LIMIT in MySQL is zero-based, so I have to initialize the index to 0
        $index = ($page - 1) * $rows;

        // Define and set the query
        $words = explode(" ", $query);
        if (count($words) == 1) { // then it's a table
            $params = null;
            $result = self::table($query);
        } else { // this would be a query
            self::query($query, $params);
            $result = self::getResult();
        }

        // Find links to other pages:
        // the first, previous, next and last pages
        $records = count($result);
        $pages = ($rows > 0) ? ceil($records/$rows) : 0;
        $previous = max($page - 1, 1);
        $next = min($page + 1, $pages);

        // set the status of the page:
        // ie the rows being currently displayed
        $firstRow = (($page - 1) * $rows) + 1;
        $lastRow = min($records, $firstRow + ($rows - 1));
        $status = ($records > 1 ? "$firstRow - $lastRow of $records" : $records). " result". ($records == 1 ? "" : "s"). " found";

        $links = array(
            "first" => $records > 0 ? 1 : 0,
            "prev" => $records > 0 ? $previous : 0,
            "page" => $records > 0 ? $page : 0,
            "next" => $records > 0 ? $next : 0,
            "last" => $records > 0 ? $pages : 0,
            "firstRecord" => $firstRow
        );

        $pos = stripos($query, "LIMIT");
        $limitString = (is_numeric($pos)) ? substr($query, $pos) : NULL;

        $reQuery = (count($words) == 1) ? "SELECT * FROM ". $query. " LIMIT ". $index. ", ". $rows : str_replace($limitString, " LIMIT ". $index. ", ". $rows, $query);
        self::query($reQuery);

        return [
            "data" => self::getResult(),
            "status" => $status,
            "links" => $links
        ];
    }

    public static function hasTable($table) {
        $apiTables = array_map('strtolower', self::tables());
        $allTableNames = array_merge(self::tables(), $apiTables);
        return in_array($table, $allTableNames);
    }

    public static function table($table) {
        $query = "SELECT * FROM $table";
        self::query($query);
        return self::getResult();
    }

    public static function row($table, $id) {
        $query = "SELECT * FROM $table WHERE id = :id";
        self::query($query, [
            ":id" => $id
        ]);
        $result = self::getResult();
        $row = (!empty($result)) ? $result[0] : null;
        return $row;
    }

    public static function exists($table, $id) {
        $row = self::row($table, $id);
        return (isset($row) && is_array($row) && !empty($row)) ? true : false;
    }

    public static function tables() {
        self::query("SHOW TABLES");
        $r = self::getResult();
        $tables = array();
        foreach ($r as $key => $value) {
            $index = "Tables_in_". self::$db;
            $tables[] = $value[$index];
        }
        return $tables;
    }

    public static function fields($table) {
        self::query("DESC $table");
        return self::getResult();
    }

    public static function fieldNames($table) {
        $f = self::fields($table);
        $fn = array();
        foreach ($f as $key => $value) {
            $fn[] = $value['Field'];
        }
        return $fn;
    }
}

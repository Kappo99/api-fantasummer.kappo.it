<?php

require_once PATH_QUERY;

class DataBase
{
    private static $dbConnection = null;
    private static $returnType = null;

    public static function initialize (int $returnType = DataBaseReturnType::ASSOCIATIVE_ARRAY)
    {
        if (self::$dbConnection !== null)
            return;

        $dbConnection = @new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($dbConnection->connect_errno)
            throw new DataBaseConnectionException(
                $dbConnection->connect_error . " (ERROR " . $dbConnection->connect_errno . ")"
            );

        self::$dbConnection = $dbConnection;
        self::$returnType = $returnType;
    }

    public static function close ()
    {
        return self::$dbConnection->close();
    }

    private static function prepareQuery (Query $query)
    {
        $operation = self::$dbConnection->prepare($query->getQuery());
        if ($operation == false)
            throw new Exception("Query sintax error: " . self::$dbConnection->error);

        $valid = $operation->bind_param($query->getParametersType(), ...$query->getParameters());
        if ($valid == false)
            throw new Exception("Query parameters error");

        return $operation;
    }

    private static function executeQueryNoPrepare (Query $query)
    {
        $result = self::$dbConnection->query($query->getQuery());

        if ($result == false)
            throw new Exception("Query sintax error: " . self::$dbConnection->error);

        if ($query->getAction() == QueryActions::SELECT) {
            if (self::$returnType == DataBaseReturnType::ASSOCIATIVE_ARRAY)
                return $result->fetch_all(MYSQLI_ASSOC);
            else if (self::$returnType == DataBaseReturnType::OBJECTS)
                return self::fetch_all_objects($result);
            throw new ReturnTypeNotFoundException();
        } else if ($query->getAction() == QueryActions::INSERT)
            return mysqli_insert_id(self::$dbConnection);
        else
            return mysqli_affected_rows(self::$dbConnection);
    }

    private static function executeQueryPrepare (Query $query)
    {
        $operation = self::prepareQuery($query);
        $valid = $operation->execute();

        if ($valid == false)
            throw new Exception("Query execution error: " . self::$dbConnection->error);

        if ($query->getAction() == QueryActions::SELECT) {
            if (self::$returnType == DataBaseReturnType::ASSOCIATIVE_ARRAY)
                return $operation->get_result()->fetch_all(MYSQLI_ASSOC);
            else if (self::$returnType == DataBaseReturnType::OBJECTS)
                return self::fetch_all_objects($operation->get_result());
            throw new ReturnTypeNotFoundException();
        } else if ($query->getAction() == QueryActions::INSERT)
            return $operation->insert_id;
        else
            return $operation->affected_rows;
    }

    public static function executeQuery (Query $query, bool $singleAsArray = true)
    {
        if ($query->hasParameters())
            $return = self::executeQueryPrepare($query);
        else
            $return = self::executeQueryNoPrepare($query);

        if (!$singleAsArray && is_countable($return) && count($return) == 1)
            return $return[0];

        return $return;
    }

    private static function fetch_all_objects (&$result)
    {
        $objects = [];
        while ($object = mysqli_fetch_object($result))
            $objects[] = $object;
        return $objects;
    }

    public static function beginTransaction ()
    {
        self::$dbConnection->autocommit(false);
    }

    public static function commitTransaction ()
    {
        $result = self::$dbConnection->commit();
        self::$dbConnection->autocommit(true);

        if (!$result)
            throw new Exception("Query commit error");
    }

}

class DataBaseConnectionException extends Exception
{
    public function __construct($error)
    {
        parent::__construct($error);
    }
}

class ReturnTypeNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Type of query result not found");
    }
}

class DataBaseReturnType
{
    const ASSOCIATIVE_ARRAY = 0;
    const OBJECTS = 1;
}

?>
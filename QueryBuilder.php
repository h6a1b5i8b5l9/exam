<?php
abstract class QueryBuilder {
    private static $results, $pdo, $query;


    public static function query($action = null, $table, $parameters = [])
    {
        try {
            self::$pdo = new PDO("mysql:host=".'localhost'.";dbname=".'exam','root','root');
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        if(!isset($action)) {
            return false;
        }
        switch ($action) {
            case 'get':
                return self::get($table, $parameters);

            case 'insert':
                return self::insert($table, $parameters);

            case 'delete':
                return self::delete($table, $parameters);

            case 'update':
                return self::update($table, $parameters);

            default:
                return false;

        }

    }



    private static function get($table, $parameters = []) {

        $field = $parameters[0];
        $operator = $parameters[1];
        $value = $parameters[2];


        if(!is_numeric($value)) {
            $value = "'$value'";
        }

        $sql = "SELECT * FROM {$table} WHERE {$field} {$operator} {$value}";
//        var_dump($sql);die;
        self::$query = self::$pdo->prepare($sql);

        self::$query->execute();
        self::$results = self::$query->fetchAll(PDO::FETCH_OBJ);


        return self::getResults();

    }



    private static function insert($table, $parameters): bool
    {
        $values = '';
        $fields = '';
        foreach ($parameters as $key => $value){
            $values .= "'$value',";
            $fields .= "`$key`, ";
        }
        $values = rtrim($values, ",");
        $fields = rtrim($fields, ", ");


        $sql = "INSERT INTO {$table} (" .  ($fields) . ") VALUES (" .  ($values) . ")";

        self::$query = self::$pdo->prepare($sql);
        if(self::$query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    private static function delete($table, $parameters): bool
    {
        $field = $parameters[0];
        $operator = $parameters[1];
        $value = $parameters[2];


        if(!is_numeric($value)) {
            $value = "'$value'";
        }

        $sql = "DELETE FROM {$table} WHERE {$field} {$operator} {$value}";

        self::$query = self::$pdo->prepare($sql);
        if(self::$query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    private static function update($table, $parameters): bool
    {
        $fields = $parameters[0];
        $condition = $parameters[1];

        $set = '';
        foreach($fields as $key => $value) {
            $set .= "{$key} = '{$value}',";
        }
        $set = rtrim($set, ',');

        $field = $condition[0];
        $operator = $condition[1];
        $value = $condition[2];

        if(!is_numeric($value)) {
            $value = "'$value'";
        }

        $sql = "UPDATE {$table} SET {$set} WHERE {$field} {$operator} {$value}";

        self::$query = self::$pdo->prepare($sql);
        if(self::$query->execute()) {
            return true;
        } else {
            return false;
        }

    }

    private static function getResults() {
        return self::$results;
    }
}

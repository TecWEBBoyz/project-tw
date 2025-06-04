<?php

namespace PTW\Services;

use PDO;
use PDOException;
use function PTW\config;

class DBService
{
    private string $dns;
    private ?PDO $pdo;
    private array $options;

    public function __construct()
    {
        $host = config('database.host');
        $database = config('database.database');

        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $this->dns = "mysql:host={$host};port=3306;dbname={$database};";
    }

    public function Connect():  bool
    {
        try {
            $this->pdo = new PDO($this->dns, config("database.username"), config("database.password"), $this->options);
            return true;
        } catch (PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function Disconnect():  void
    {
        $this->pdo = null;
    }

    public function Query(string $query, array $parameters = []) : array
    {
        $this->Connect();
        $preparedQuery = $this->pdo->prepare($query);
        $preparedQuery->execute($parameters);

        $this->Disconnect();

        return $preparedQuery->fetchAll();
    }

    public function Count(string $table, array|null $filter) : int
    {
        $where = "";
        if (is_array($filter) && !empty($filter)) {
            $where = "WHERE ";
            $first = true;
            foreach ($filter as $key => $value) {
                if (!$first) {
                    $where .= " AND ";
                }
                $where .= $key . "='" . $value . "'";
                $first = false;
            }
        }

        $query = "SELECT COUNT(*) FROM {$table} {$where}";

        return $this->Query($query)[0]["COUNT(*)"];
    }
    public function All(string $table, array|string $columns = '*') : array
    {
        $columns = $this->ColumnToString($columns);

        $query = "SELECT {$columns} FROM {$table}";

        return $this->Query($query);
    }

    public function GetPaged(string $table, int $offset, int $size, array|string $columns = '*') : array
    {
        $columns = $this->ColumnToString($columns);

        $query = "SELECT {$columns} FROM {$table} LIMIT {$size} OFFSET {$offset}";

        return $this->Query($query);
    }

    public function FindElementByID(string $table, string $id, array|string $columns = '*') : array | null
    {
        $columns = $this->ColumnToString($columns);

        $query = "SELECT {$columns} FROM {$table} WHERE id = ?";
        $arr = $this->Query($query, [$id]);

        if(count($arr) > 0)
            return $arr[0];

        return null;
    }

    public function FindElementByColumn(string $table, string $uniqueColumn, string $uniqueValue, array|string $columns = '*') : array | null
    {
        $arr = $this->FindElementsByColumn($table, $uniqueColumn, $uniqueValue);

        if(count($arr) > 0)
            return $arr[0];

        return null;
    }
    public function FindElementsByColumn(string $table, string $uniqueColumn, string $uniqueValue, array|string $columns = '*') : array | null
    {
        $columns = $this->ColumnToString($columns);
        $uniqueColumn = $this->ColumnToString($uniqueColumn);

        $query = "SELECT {$columns} FROM {$table} WHERE {$uniqueColumn} = ?";
        return $this->Query($query, [$uniqueValue]);;
    }

    public function LastInsertedID() : string|int
    {
        return $this->pdo->lastInsertId();
    }

    public function Create(string $table, array|string $columns, array|string $values): bool
    {
        $columns = $this->ColumnToString($columns);
        $values = is_array($values) ? implode(',', $values) : $values;

        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

        return !!$this->Query($query);
    }

    public function Update(string $table, string $id, array|string $columns, array|string $values): bool
    {
        $set = implode(", ", array_map(
            fn(string $column, string $value) => "{$this->RemoveNonAlphaCharacter($column)} = {$value}", $columns, $values));
        $set = str_replace("= \"NULL\"", "= NULL", $set);
        $query = "UPDATE {$table} SET {$set} WHERE id = \"{$id}\"";
        echo $query;
        return !!$this->Query($query);
    }

    public function Delete(string $table, string $id): bool
    {
        $query = "DELETE FROM {$table} WHERE id = '{$id}'";
        return !!$this->Query($query);
    }

    private function ColumnToString(array|string $columns) : string
    {
        if (is_array($columns)) {
            foreach ($columns as $column)
                // Remove all the non alphabet characters.
                $column = $this->RemoveNonAlphaCharacter($column);
            $column = "\"" . $column . "\"";

            $columns = implode(', ', $columns);
        }

        return $columns;
    }

    private function RemoveNonAlphaCharacter(string $str) : string
    {
        return preg_replace("/[^a-zA-Z_-]/", "", $str);
    }
}
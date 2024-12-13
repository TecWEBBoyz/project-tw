<?php

namespace PTW\Modules\Database;

use PDO;
use PDOException;
use PDOStatement;
use function PTW\config;


class DB
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
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Imposta la modalità di recupero su ASSOC
        ];

        $this->dns = "mysql:host={$host};port=3306;dbname={$database};";
    }

    public function Connect():  bool
    {
        try {
            $this->pdo = new PDO($this->dns, config("database.username"), config("database.password"), $this->options);
            return true;
        } catch (PDOException $e) {
            echo "Errore nella connessione al database: " . $e->getMessage();
            return false;
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

    public function All(string $table, array|string $columns = '*') : array
    {
        $columns = $this->ColumnToString($columns);

        $query = "SELECT {$columns} FROM {$table}";

        return $this->Query($query);
    }

    public function GetPaged(string $table, int $offset, int $size, array|string $columns = '*') : array
    {
        $columns = $this->ColumnToString($columns);

        $query = "SELECT {$columns} FROM {$this->table} LIMIT {$size} OFFSET {$offset}";

        return $this->Query($query);
    }

    public function FindElementByID(string $table, string $id, array|string $columns = '*') : array
    {
        $columns = $this->ColumnToString($columns);

        $query = "SELECT {$columns} FROM {$table} WHERE id = ?";

        return $this->Query($query, [$id])[0];
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

        $query = "UPDATE {$table} SET {$set} WHERE id = \"{$id}\"";
        return !!$this->Query($query);
    }

    public function Delete(string $table, string $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = {$id}";
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
        return preg_replace('/[^a-zA-Z]*/', '', $str);
    }
}
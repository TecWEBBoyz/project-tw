<?php

namespace PTW\Repositories;

use PTW\Models\DBItem;
use PTW\Services\DBService;

class BaseRepository
{
    protected DBService $database;

    protected string $table;
    protected DBItem $element_class;

    public function __construct(string $table)
    {
        $this->database = new DBService();
        $this->table = $table;
    }

    /**
     * @return DBItem[]
     */
    public function All(): array
    {
        $res = $this->database->All($this->table);

        if (!$res)
            return [];

        return $this->CreateInstances($res);
    }

    /**
     * @param int $index
     * @param int $maxSize
     * @return DBItem[]
     */
    public function GetPage(int $index, int $maxSize): array
    {
        $offset = ($index - 1) * $maxSize;
        $res = $this->database->GetPaged($this->table, $offset, $maxSize);

        return $this->CreateInstances($res);
    }

    public function Count(array $filter = []): int
    {
        return $this->database->Count($this->table, $filter);
    }

    public function Create(DBItem $element): bool
    {
        $data = $element->toArray();
        return $this->database->Create($this->table, $data);
    }

    public function Update(string $id, DBItem $element): bool
    {
        $data = $element->toArray();
        return $this->database->Update($this->table, $id, array_keys($data),
            array_map(fn($col) => "\"" . $col . "\"", array_values($data)));
    }

    public function Delete(string $id): bool
    {
        return $this->database->Delete($this->table, $id);
    }

    public function ExistsId(string $id): bool
    {
        return !!$this->GetElementByID($id);
    }

    public function GetElementByID(string $id): DBItem|null
    {
        $data = $this->database->FindElementByID($this->table, $id);
        return $this->CreateInstance($data);
    }

    public function GetElementsByColumn(string $column, string $value): array|null
    {
        $data = $this->database->FindElementsByColumn($this->table, $column, $value);
        return $this->CreateInstances($data);
    }

    public function GetElementByUnique(string $uniqueCol, string $uniqueValue): DBItem|null
    {
        $data = $this->database->FindElementByColumn($this->table, $uniqueCol, $uniqueValue);
        return $this->CreateInstance($data);
    }

    public function CreateInstance(array|null $data): DBItem|null
    {
        if (is_null($data))
            return null;
        if (empty($this->element_class))
            return null;

        $instance = new $this->element_class;
        if (method_exists($instance, 'setData')) {
            $fields = array_map(function ($value) {
                return $value;
            }, $data);

            $instance->SetData($fields);
        } else {
            foreach ($data as $key => $value)
                $instance[$key] = $value;
        }

        return $instance;
    }

    /**
     * @param array $data
     * @return DBItem[]
     */
    public function CreateInstances(array $data): array
    {
        if (key_exists('id', $data))
            return [$this->CreateInstance($data)];

        $instances = [];

        foreach ($data as $item) {
            $instances[] = $this->CreateInstance($item);
        }

        return $instances;
    }

}
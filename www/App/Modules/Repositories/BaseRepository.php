<?php

namespace PTW\Modules\Repositories;

use PTW\App;
use PTW\Modules\Database\DB;
use PTW\Models\DBItem;
use function PTW\dd;

class BaseRepository
{
    protected DB $database;
    protected string $table;
    protected DBItem $element_class;

    public function __construct(string $table) {
        $this->database = App::GetDatabase();
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

    public function Count(): int
    {
        return $this->database->Count($this->table);
    }

    public function Create(DBItem $element): bool
    {
        $data = $element->ToArray();
        return $this->database->Create($this->table, array_keys($data),
            array_map(fn($col) => "\"" . $col . "\"", array_values($data)));
    }

    public function Update(string $id, DBItem $element): bool
    {
        $data = $element->ToArray();
        return $this->database->Update($this->table, $id, array_keys($data),
            array_map(fn($col) => "\"" . $col . "\"", array_values($data)));
    }

    public function Delete(string $id): bool
    {
        return $this->database->Delete($this->table, $id);
    }

    public function GetElementByID(string $id): DBItem|null
    {
        $data = $this->database->FindElementByID($this->table, $id);
        return $this->CreateInstance($data);
    }

    public function CreateInstance(array|null $data): DBItem | null
    {
        if (is_null($data))
            return null;
        if (empty($this->element_class))
            return null;

        $data = $this->element_class->FilterData($data);

        $instance = new $this->element_class;
        if (method_exists($instance, 'SetData'))
        {
            $fields = array_map(function ($value) {
                return $value;
            }, $data);

            $instance->SetData($fields);
        }
        else
        {
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
        $instances = [];

        foreach ($data as $item)
        {
            $instances[] = $this->CreateInstance($item);
        }

        return $instances;
    }

}
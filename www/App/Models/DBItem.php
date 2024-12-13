<?php

namespace PTW\Models;

use PTW\Contracts\DBItemContracts;

abstract class DBItem implements DBItemContracts
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function ToArray(): array
    {
        return $this->data;
    }

    public function __toString(): string {
        $output = [];
        foreach ($this->data as $key => $value) {
            $output[] = ucfirst($key) . ": " . $value;
        }
        return implode(", ", $output);
    }

    protected function FilterDataUtility(array $data, array $allowedKeys): array
    {
        return array_filter(
            $data,
            fn ($value, $key) => in_array($key, array_column($allowedKeys, "value"), true),
            ARRAY_FILTER_USE_BOTH
        );
    }
}
<?php

namespace PTW\Models;

interface DBItem
{
    public function setData(array $data): void;
    public function toArray(): array;
    public function filterData(array $data): array;
}
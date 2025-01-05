<?php

namespace PTW\Models;

use function PTW\dd;

class Image extends DBItem
{
    /**
     * @var array<ImageType, mixed>
     */
    protected array $data;

    /**
     * @param array<ImageType, mixed> $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @throws \Exception
     *@var array<ImageType, mixed> $data
     */
    public function SetData(array $data): void
    {
        if (!$this->ValidateArray($data))
            throw new \Exception("Invalid data array");

        $this->data = $data;
    }

    public function FilterData(array $data): array
    {
        return $this->FilterDataUtility($data, ImageType::cases());
    }

    private function ValidateArray(array $data): bool
    {
        foreach ($data as $key => $_)
        {
            if (!ImageType::tryFrom($key))
                return false;
        }

        return true;
    }
}
<?php

namespace PTW\Models;

use function PTW\dd;

class Booking extends DBItem
{
    /**
     * @var array<BookingType, mixed>
     */
    protected array $data;

    /**
     * @param array<BookingType, mixed> $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @throws \Exception
     *@var array<BookingType, mixed> $data
     */
    public function SetData(array $data): void
    {
        if (!$this->ValidateArray($data))
            throw new \Exception("Invalid data array");

        $this->data = $data;
    }

    public function FilterData(array $data): array
    {
        return $this->FilterDataUtility($data, BookingType::cases());
    }

    private function ValidateArray(array $data): bool
    {
        foreach ($data as $key => $_)
        {
            if (!BookingType::tryFrom($key))
                return false;
        }

        return true;
    }
}
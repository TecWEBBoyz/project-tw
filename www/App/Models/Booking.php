<?php

namespace PTW\Models;

use DateTime;
use Exception;

class Booking implements DBItem
{
    protected string $userId;
    protected string $serviceId;
    protected DateTime $date;
    protected int $numberOfPeople;
    protected string $notes;

    /**
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        if (empty($data))
            return;

        try {
            $this->setData($data);
        } catch (\Throwable $e) {
            throw new Exception("Error in Booking constructor: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function setData(array $data): void
    {
        if (!$this->ValidateArray($data))
            throw new Exception("Invalid data array");

        $this->userId = $data['user_id'];
        $this->serviceId = $data['service_id'];
        $this->date = new DateTime($data['date']);
        $this->numberOfPeople = $data['num_people'];
        $this->notes = $data['notes'] ?? '';
    }

    private function validateArray(array $data): bool
    {
        if (!isset($data['user_id']) || !isset($data['service_id']) ||
            !isset($data['date']) || !isset($data['num_people'])) {
            return false;
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'service_id' => $this->serviceId,
            'date' => $this->date->format('Y-m-d'),
            'num_people' => $this->numberOfPeople,
            'notes' => $this->notes
        ];
    }

    public function filterData(array $data): array
    {
        return [
            "user_id" => $data['user_id'],
            "service_id" => $data['service_id'],
            "date" => new DateTime($data['date']),
            "number_of_people" => $data['number_of_people'],
            "notes" => $data['notes'] ?? '',
        ];
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getNumberOfPeople(): int
    {
        return $this->numberOfPeople;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }
}
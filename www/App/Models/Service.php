<?php

namespace PTW\Models;

use Exception;

class Service implements DBItem
{
    protected string $id;
    protected string $name;
    protected string $description;
    protected float $prince;
    protected int $duration;
    protected int $maxPeople;

    /**
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        try {
            $this->setData($data);
        } catch (\Throwable $e) {
            throw new Exception("Error in Service constructor: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function setData(array $data): void
    {
        if (!$this->ValidateArray($data))
            throw new Exception("Invalid data array");

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'] ?? '';
        $this->prince = $data['prince'];
        $this->duration = $data['duration'];
        $this->maxPeople = $data['max_people'];
    }

    private function validateArray(array $data): bool
    {
        if (!isset($data['id']) || !isset($data['name']) ||
            !isset($data['description']) || !isset($data['prince']) ||
            !isset($data['duration']) || !isset($data['max_people'])) {
            return false;
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'prince' => $this->prince,
            'duration' => $this->duration,
            'max_people' => $this->maxPeople
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrince(): float
    {
        return $this->prince;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getMaxPeople(): int
    {
        return $this->maxPeople;
    }
}
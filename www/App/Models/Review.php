<?php

namespace PTW\Models;

use Cassandra\Date;
use DateMalformedStringException;
use DateTime;
use Exception;

class Review implements DBItem
{
    protected string $id;
    protected string $userId;
    protected string $animalId;
    protected int $rating;
    protected string $comment;
    protected DateTime $createdAt;
    protected DateTime $updatedAt;

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
            throw new Exception("Error in Review constructor: " . $e->getMessage());
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
        $this->userId = $data['user_id'];
        $this->animalId = $data['animal_id'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'] ?? "";
        $this->createdAt = new DateTime($data['created_at']);
        $this->updatedAt = new DateTime($data['updated_at']);
    }

    private function validateArray(array $data): bool
    {
        if (!isset($data['id']) || !isset($data['user_id']) ||
            !isset($data['animal_id']) || !isset($data['rating']) ||
            !isset($data['created_at']) || !isset($data['updated_at'])) {
            return false;
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'animal_id' => $this->animalId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @throws DateMalformedStringException
     */
    public function filterData(array $data): array
    {
        return [
            "id" => $data['id'],
            "user_id" => $data['user_id'],
            "animal_id" => $data['animal_id'],
            "rating" => $data['rating'],
            "comment" => $data['comment'] ?? "",
            "created_at" => new DateTime($data['created_at']),
            "updated_at" => new DateTime($data['updated_at']),
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getAnimalId(): string
    {
        return $this->animalId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
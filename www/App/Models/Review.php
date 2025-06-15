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
        $this->rating = $data['rating'];
        $this->comment = $data['comment'] ?? "";
        $this->createdAt = new DateTime($data['created_at']);
        $this->updatedAt = new DateTime($data['updated_at']);
    }

    private function validateArray(array $data): bool
    {
        // Verifica solo i campi obbligatori
        if (!isset($data['user_id']) || !isset($data['rating'])) {
            return false;
        }

        // Controlla che il rating sia un numero valido
        if (!is_int($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return false;
        }

        return true;
    }

    public function toArray(): array
    {
        $result = [];

        if (isset($this->id)) {
            $result['id'] = $this->id;
        }
        if (isset($this->userId)) {
            $result['user_id'] = $this->userId;
        }
        if (isset($this->rating)) {
            $result['rating'] = $this->rating;
        }
        if (isset($this->comment)) {
            $result['comment'] = $this->comment;
        }
        if (isset($this->createdAt)) {
            $result['created_at'] = $this->createdAt->format('Y-m-d H:i:s');
        }
        if (isset($this->updatedAt)) {
            $result['updated_at'] = $this->updatedAt->format('Y-m-d H:i:s');
        }

        return $result;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function filterData(array $data): array
    {
        return [
            "id" => $data['id'],
            "user_id" => $data['user_id'],
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
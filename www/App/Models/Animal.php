<?php

namespace PTW\Models;

use Exception;

class Animal implements DBItem
{
    protected string $id;
    protected string $species;
    protected string $name;
    protected string $age;
    protected string $habitat;
    protected string $dimensions;
    protected string $lifespan;
    protected string $diet;
    protected string $description;
    protected string $image;

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
            throw new Exception("Error in Animal constructor: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function setData(array $data): void
    {
        if (!$this->validateArray($data))
            throw new Exception("Invalid data array");

        $this->id = $data['id'];
        $this->species = $data['species'];
        $this->name = $data['name'];
        $this->age = $data['age'];
        $this->habitat = $data['habitat'] ?? '';
        $this->dimensions = $data['dimensions'] ?? '';
        $this->lifespan = $data['lifespan'] ?? '';
        $this->diet = $data['diet'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->image = $data['image'];
    }

    private function validateArray(array $data): bool
    {
        if (!isset($data['id']) || !isset($data['species']) ||
            !isset($data['name']) || !isset($data['age']) ||
            !isset($data['habitat']) || !isset($data['dimensions']) ||
            !isset($data['lifespan']) || !isset($data['diet']) ||
            !isset($data['description']) || !isset($data['image'])) {
            return false;
        }
        return true;
    }

    public function filterData(array $data): array
    {
        return [
            "id" => $data['id'],
            "species" => $data['species'],
            "name" => $data['name'],
            "age" => $data['age'],
            "habitat" => $data['habitat'] ?? '',
            "dimensions" => $data['dimensions'] ?? '',
            "lifespan" => $data['lifespan'] ?? '',
            "diet" => $data['diet'] ?? '',
            "description" => $data['description'] ?? '',
            "image" => $data['image'],
        ];
    }

    public function setDataFromForm(array $data): void
    {
        $required_keys = ['species', 'name', 'age', 'habitat', 'dimensions', 'lifespan', 'diet', 'description', 'image'];
        foreach ($required_keys as $key) {
            if (!isset($data[$key])) {
                throw new Exception("Dato mancante nel form: " . $key);
            }
        }
        
        $this->species = $data['species'];
        $this->name = $data['name'];
        $this->age = $data['age'];
        $this->habitat = $data['habitat'];
        $this->dimensions = $data['dimensions'];
        $this->lifespan = $data['lifespan'];
        $this->diet = $data['diet'];
        $this->description = $data['description'];
        $this->image = $data['image'];
    }

    public function toArray(): array
    {
        $arrayData = [
            'name' => $this->name,
            'species' => $this->species,
            'age' => $this->age,
            'habitat' => $this->habitat,
            'dimensions' => $this->dimensions,
            'lifespan' => $this->lifespan,
            'diet' => $this->diet,
            'description' => $this->description,
            'image' => $this->image
        ];
        
        if (isset($this->id)) {
            $arrayData['id'] = $this->id;
        }

        return $arrayData;
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): string
    {
        return $this->age;
    }

    public function getHabitat(): string
    {
        return $this->habitat;
    }

    public function getDimensions(): string
    {
        return $this->dimensions;
    }

    public function getLifespan(): string
    {
        return $this->lifespan;
    }

    public function getDiet(): string
    {
        return $this->diet;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
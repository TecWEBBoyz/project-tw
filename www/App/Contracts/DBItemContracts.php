<?php

namespace PTW\Contracts;

use PTW\Models\UserType;

interface DBItemContracts
{
    /**
     * Update all the field inside an object.
     *
     * @param array $data
     * @return void
     */
    public function SetData(array $data) : void;

    /**
     * Filter data based on the object attributes.
     *
     * @param array $data
     * @return array
     */
    public function FilterData(array $data) : array;

    /**
     * Convert the object to an array form.
     *
     * @return array
     */
    public function ToArray(): array;

    /**
     * Convert the object to string form to enable echoing.
     *
     * @return string
     */
    public function __toString(): string;
}
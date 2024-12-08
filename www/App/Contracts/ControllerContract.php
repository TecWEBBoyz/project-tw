<?php

/**
 * Rappresentation og a controller in the application.
 */

namespace PTW\Contracts;

interface ControllerContract
{
    /**
     * Response to a GET request.
     *
     * @return void
     */
    public function get(): void;

    /**
     * Response to a POST request.
     * @return void
     */
    public function post(): void;

    /**
     * Response to a PUT request.
     * @return void
     */
    public function put(): void;

    /**
     * Response to a DELETE request.
     * @return void
     */
    public function delete(): void;
}
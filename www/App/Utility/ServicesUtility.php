<?php

namespace PTW\Utility;

use PTW\Models\Services;

class ServicesUtility
{
    public static function CheckService(string $service): bool
    {
        return match ($service) {
            Services::events->value, Services::other->value => true,
            default => false,
        };
    }

    public static function CheckSelectedService(string $service): bool
    {
        return match ($service) {
            Services::no_category->value => false,
            default => true,
        };
    }
}
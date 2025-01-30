<?php

namespace PTW\Models;

enum BookingType: string
{
    case id = 'id';
    case user = 'user';
    case status = 'status';
    case notes= 'notes';
    case service = 'service';
    case date = 'date';
    case created_at = "created_at";
    case updated_at = "updated_at";
}

enum Services: string
{
    case events = 'events';
    case other = 'other';
    case no_category = 'none';
}

class ServicesUtility {
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

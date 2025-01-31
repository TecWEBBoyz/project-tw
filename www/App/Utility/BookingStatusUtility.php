<?php

namespace PTW\Utility;

use PTW\Models\BookingStatus;

class BookingStatusUtility {
    public static function CheckStatus(string $status): bool
    {
        return match ($status) {
            BookingStatus::cancelled->value, BookingStatus::confirmed->value, BookingStatus::pending->value  => true,
            default => false,
        };
    }
}
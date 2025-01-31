<?php

namespace PTW\Utility;

class ImageCategoryUtility {
    public static function CheckCategory(string $category): bool
    {
        return match ($category) {
            \PTW\Models\ImageCategory::Travels->value, \PTW\Models\ImageCategory::RacingCars->value, \PTW\Models\ImageCategory::Events->value, \PTW\Models\ImageCategory::no_category->value => true,
            default => false,
        };
    }

    public static function CheckCategorySelected(string $category): bool
    {
        return match ($category) {
            \PTW\Models\ImageCategory::no_category->value => false,
            default => true,
        };
    }
}
<?php

namespace PTW\Models;

enum ImageCategory: string
{
    case no_category = 'none';
    case Travels = 'Travels';
    case RacingCars = 'Racing-Cars';
    case Events = 'Events';
}
class ImageCategoryUtility {
    public static function CheckCategory(string $category): bool
    {
        return match ($category) {
            ImageCategory::Travels->value, ImageCategory::RacingCars->value, ImageCategory::Events->value, ImageCategory::no_category->value => true,
            default => false,
        };
    }

    public static function CheckCategoryNotSelected(string $category): bool
    {
        return match ($category) {
            ImageCategory::no_category->value => true,
            default => false,
        };
    }
}
enum ImageType: string
{
    case id = 'id';
    case path = 'path';
    case title = 'title';
    case alt = 'alt';
    case description = 'description';
    case place = 'place';
    case date = "date";
    case visible = "visible";
    case category = "category";
    case created_at = "created_at";
    case updated_at = "updated_at";
}

<?php

namespace PTW\Models;

enum ImageCategory: string
{
    case no_category = 'none';
    case Travels = 'Travels';
    case RacingCars = 'Racing-Cars';
    case Events = 'Events';
}
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

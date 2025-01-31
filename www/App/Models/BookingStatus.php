<?php

namespace PTW\Models;

enum BookingStatus: string
{
    case pending = 'pending';
    case confirmed = 'confirmed';
    case cancelled = 'cancelled';

}
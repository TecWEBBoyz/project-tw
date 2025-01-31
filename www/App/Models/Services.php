<?php

namespace PTW\Models;

enum Services: string
{
    case events = 'events';
    case other = 'other';
    case no_category = 'none';
}
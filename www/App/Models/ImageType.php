<?php

namespace PTW\Models;

enum ImageType: string
{
    case id = 'id';
    case path = 'path';
    case title = 'title';
    case alt = 'alt';
    case description = 'description';
    case place = 'place';
    case date = "date";
    case updated_at = "updated_at";
}

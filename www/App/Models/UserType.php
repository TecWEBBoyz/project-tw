<?php

namespace PTW\Models;

enum UserType: string
{
    case id = 'id';
    case name = 'name';
    case email = 'email';
    case telephone = 'telephone';
    case password = 'password';
    case ruolo = 'ruolo';
}
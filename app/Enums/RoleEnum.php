<?php

namespace App\Enums;

enum RoleEnum: int
{
    case GUEST = 0;
    case USER = 1;
    case VOLUNTEER=2;
    case OFFICIAL = 3;
    case ADMIN = 4;
    case SUPERADMIN = 5;
}

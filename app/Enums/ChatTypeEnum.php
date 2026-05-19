<?php

namespace App\Enums;

enum ChatTypeEnum: int
{
    case DEFAULT = 0;
    case NOTIFICATION = 1;
    case REPORT = 2;
    case USER = 3;
    case SYSTEM=4;
}

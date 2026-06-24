<?php

namespace App\Domains\Restaurant\Enums;

enum MemberRole: string
{
    case Owner = 'owner';
    case Manager = 'manager';
    case Cashier = 'cashier';
}

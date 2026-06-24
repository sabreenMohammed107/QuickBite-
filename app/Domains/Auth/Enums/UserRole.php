<?php

namespace App\Domains\Auth\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case RestaurantOwner = 'restaurant_owner';
    case DeliveryAgent = 'delivery_agent';
    case Admin = 'admin';
}

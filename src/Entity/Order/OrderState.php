<?php

namespace Src\Entity\Order;

enum OrderState: string {
    case PENDING = 'pending';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
}
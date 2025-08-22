<?php

namespace Src\Entity\Order;

enum OrderState: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case SHIPPED = 'shipped';
}
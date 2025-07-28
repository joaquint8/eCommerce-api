<?php

namespace Src\Entity\Product;

enum ProductState: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case OUT_OF_STOCK = 'outOfStock';
    case HIDDEN = 'hidden';
}
<?php

namespace App\Extensions;

use App\Entity\Product;
use App\Extensions\UserRelationExtansion;

class ProductExtansion extends UserRelationExtansion
{
    /**
     * @return string
     */
    public function getResourceClass(): string
    {
        return Product::class;
    }
}

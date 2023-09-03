<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class ProductEntityListener
{
    public function postPersist(Product $product, PostPersistEventArgs $eventArgs)
    {
        $test = $eventArgs->getObjectManager()->getUnitOfWork()->getEntityChangeSet($product);
    }

    public function prePersist(Product $product, LifecycleEventArgs $eventArgs)
    {
        // $test = 1;
        $newName = $product->getName() . rand(10000, 99999);

        $product->setName($newName);
    }

    public function postUpdate(Product $product, LifecycleEventArgs $eventArgs)
    {
        $test = 1;
    }

    public function preUpdate(Product $product, LifecycleEventArgs $eventArgs)
    {
        $test = 1;
    }
}

<?php

namespace App\Validator\Constraints;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class ProductConstraintValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductConstraint) {
            throw new UnexpectedTypeException($constraint, ProductConstraint::class);
        }

        if (!$value instanceof Product) {
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        $this->context->addViolation("Error");
    }
}
<?php

namespace App\Validator\Constraints;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class CategoryConstraintValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryConstraint) {
            throw new UnexpectedTypeException($constraint, CategoryConstraint::class);
        }

        if (!$value instanceof Category) {
            throw new UnexpectedTypeException($constraint, Category::class);
        }

        $this->context->addViolation("Error");
    }
}
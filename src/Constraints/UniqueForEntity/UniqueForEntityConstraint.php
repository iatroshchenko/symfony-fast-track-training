<?php


namespace App\Constraints\UniqueForEntity;

use Symfony\Component\Validator\Constraint;


class UniqueForEntityConstraint extends Constraint
{
    public $message = 'This value is already used.';
    public $entityClass;
    public $field;

    public function getRequiredOptions()
    {
        return ['entityClass', 'field'];
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy()
    {
        return UniqueForEntityConstraintValidator::class;
    }
}
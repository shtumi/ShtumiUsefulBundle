<?php

namespace Shtumi\UsefulBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\FormException;

class EntityToPropertyTransformer implements DataTransformerInterface
{
    protected $em;
    protected $class;
    protected $property;
    protected $unitOfWork;

    public function __construct(EntityManager $em, $class, $property)
    {
        $this->em = $em;
        $this->unitOfWork = $this->em->getUnitOfWork();
        $this->class = $class;
        $this->property = $property;

    }

    public function transform($entity)
    {
        if (null === $entity) {
            return null;
        }

        if (!$this->unitOfWork->isInIdentityMap($entity)) {
            throw new FormException('Entities passed to the choice field must be managed');
        }

        if ($this->property) {
            $propertyAccessor = PropertyAccess::getPropertyAccessor();
            
            return $propertyAccessor->getValue($entity, $this->property);
        }

        return current($this->unitOfWork->getEntityIdentifier($entity));
    }


    public function reverseTransform($prop_value)
    {
        if (!$prop_value) {
            return null;
        }

        $entity = $this->em->getRepository($this->class)->findOneBy(array($this->property => $prop_value));

        return $entity;
    }
}

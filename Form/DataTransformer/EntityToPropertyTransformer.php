<?php

namespace Shtumi\UsefulBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

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
            $propertyPath = new PropertyPath($this->property);
            return $propertyPath->getValue($entity);
        }

        return current($this->unitOfWork->getEntityIdentifier($entity));
    }


    public function reverseTransform($prop_value)
    {
        if (!$prop_value) {
            return null;
        }

        $em = $this->em;
        $class = $this->class;
        $repository = $em->getRepository($class);

        $entity = $this->em->getRepository($this->class)->findOneBy(array($this->property => $prop_value));

        return $entity;
    }
}


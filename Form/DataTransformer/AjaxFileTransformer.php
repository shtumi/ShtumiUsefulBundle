<?php

namespace Shtumi\UsefulBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\FormException;

class AjaxFileTransformer implements DataTransformerInterface
{

    public function transform($data)
    {

        /*
        if (null === $entity || '' === $entity) {
            return 'null';
        }
        if (!is_object($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }
        if (!$this->unitOfWork->isInIdentityMap($entity)) {
            throw new FormException('Entities passed to the choice field must be managed');
        }

        return $entity->getId();
        */
    }

    public function reverseTransform($value)
    {


        /*
        if ('' === $id || null === $id) {
            return null;
        }

        if (!is_numeric($id)) {
            throw new UnexpectedTypeException($id, 'numeric' . $id);
        }

        $entity = $this->em->getRepository($this->class)->findOneById($id);

        if ($entity === null) {
            throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $id));
        }

        return $entity;
        */
    }
}

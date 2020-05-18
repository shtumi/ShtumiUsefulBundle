<?php

namespace Shtumi\UsefulBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\FormException;

class EntityToIdTransformer implements DataTransformerInterface
{

    protected $em;
    protected $class;
    protected $unitOfWork;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->unitOfWork = $this->em->getUnitOfWork();
        $this->class = $class;
    }

    public function transform($entity)
    {

        if (null === $entity || '' === $entity) {
            return 'null';
        }

        if (!is_object($entity) && !is_array($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }

        if(is_array($entity)){
            return array_map([$this, 'doEntityToId'], $entity);
        }else{
            return $this->doEntityToId($entity);
        }
    }

    public function reverseTransform($id)
    {
        if ('' === $id || null === $id) {
            return null;
        }

        if (!is_numeric($id) && !is_array($id)) {
            throw new UnexpectedTypeException($id, 'numeric' . $id);
        }

        if(is_array($id)){
            return array_map([$this, 'doFindEntity'], $id);
        }else{
            return $this->doFindEntity($id);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function doFindEntity($id)
    {
        $entity = $this->em->getRepository($this->class)->findOneById($id);

        if($entity === null){
            throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $id));
        }

        return $entity;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function doEntityToId($entity)
    {
        if(!$this->unitOfWork->isInIdentityMap($entity)){
            throw new FormException('Entities passed to the choice field must be managed');
        }

        return $entity->getId();
    }
}

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

        if (is_null($entity)) {
                    // TODO: write it properly - reverse class from alias.
                    //ref: https://github.com/doctrine/DoctrineBundle/edit/master/Command/GenerateEntitiesDoctrineCommand.php
                    //$name = $this->class;
                    //if (false !== $pos = strpos($name, ':')) {
                    //    $name = $this->getContainer()->get('doctrine')->getEntityNamespace(substr($name, 0, $pos)).'\\'.substr($name, $pos + 1);
                    //}
                    //echo $name;
                    //exit;
                    $baseClass = substr($this->class, strrpos($this->class, ':')+1);
                    $className = '\\PR\\Sportex\\AdminBundle\\Entity\\' . $baseClass;
                    //echo 'AdD: ' . $className;
                    $newEntity = new $className;
                    $setter = 'set' . ucfirst($this->property);
            if (method_exists($newEntity, 'setCountry')) {
                            $newEntity->setCountry($this->em->getRepository('\\PR\\Sportex\\AdminBundle\\Entity\\Country')->findOneBy(array('cName' => 'Undefined')));
            }
            // TODO: write it properly - make autocomplete with dependent entities and be able to identify what to get from there...
            if (method_exists($newEntity, 'setSport')) {

                foreach($_POST as $k => $n) {
                    if(is_array($n)) {
                        foreach($n as $kk => $nn) {
                            if ($kk == 'sport') {
                                $sportId = $nn;
                            }
                        }
                    }
                }
                $newEntity->setSport($this->em->getRepository('\\PR\\Sportex\\AdminBundle\\Entity\\Sport')->findOneBy(array('id' => $sportId)));
            }
            $newEntity->$setter($prop_value);
            $this->em->persist($newEntity);
            $this->em->flush();
        }
        $entity = $this->em->getRepository($this->class)->findOneBy(array($this->property => $prop_value));


        return $entity;
    }
}


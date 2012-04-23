<?php

namespace Shtumi\UsefulBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\FormException;
use Shtumi\UsefulBundle\Form\DataTransformer\EntityToPropertyTransformer;
use Symfony\Component\Form\AbstractType;

class AjaxAutocompleteType extends AbstractType
{

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'entity_alias'      => null,
            'class'             => null,
            'property'          => null
        );
    }

    public function getName()
    {
        return 'type_ajax_autocomplete';
    }

    public function getParent(array $options)
    {
        return 'text';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {

        $entities = $this->container->getParameter('shtumi.autocomplete_entities');

        if (null === $options['entity_alias']) {
            throw new FormException('You must provide a entity alias "entity_alias" and tune it in config file');
        }

        if (!isset ($entities[$options['entity_alias']])){
            throw new FormException('There are no entity alias "' . $options['entity_alias'] . '" in your config file');
        }

        $options['class'] = $entities[$options['entity_alias']]['class'];
        $options['property'] = $entities[$options['entity_alias']]['property'];


        $builder->prependClientTransformer(new EntityToPropertyTransformer(
            $this->container->get('doctrine')->getEntityManager(),
            $options['class'],
            $options['property']
        ));

        $builder->setAttribute('entity_alias', $options['entity_alias']);
    }

    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('entity_alias',  $form->getAttribute('entity_alias'));
    }

}
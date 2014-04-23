<?php

namespace Shtumi\UsefulBundle\Form\Type;

use Shtumi\UsefulBundle\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class DependentFilteredEntityType extends AbstractType
{

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'empty_value'       => '',
            'entity_alias'      => null,
            'parent_field'      => null
        );
    }

    public function getParent()
    {
        return 'field';
    }

    public function getName()
    {
        return 'shtumi_dependent_filtered_entity';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entities = $this->container->getParameter('shtumi.dependent_filtered_entities');
        $options['class'] = $entities[$options['entity_alias']]['class'];
        $options['property'] = $entities[$options['entity_alias']]['property'];

        $options['no_result_msg'] = $entities[$options['entity_alias']]['no_result_msg'];


        $builder->prependClientTransformer(new EntityToIdTransformer(
            $this->container->get('doctrine')->getEntityManager(),
            $options['class']
        ));


        $builder->setAttribute("parent_field", $options['parent_field']);
        $builder->setAttribute("entity_alias", $options['entity_alias']);
        $builder->setAttribute("no_result_msg", $options['no_result_msg']);
        $builder->setAttribute("empty_value", $options['empty_value']);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->set('parent_field', $form->getAttribute('parent_field'));
        $view->set('entity_alias', $form->getAttribute('entity_alias'));
        $view->set('no_result_msg', $form->getAttribute('no_result_msg'));
        $view->set('empty_value', $form->getAttribute('empty_value'));
    }

}

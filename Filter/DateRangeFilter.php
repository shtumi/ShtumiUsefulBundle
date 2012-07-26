<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shtumi\UsefulBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sonata\AdminBundle\Form\Type\BooleanType;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class DateRangeFilter extends Filter
{

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @param string $field
     * @param mixed $data
     * @return
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }

        $this->handleScalar($queryBuilder, $alias, $this->getFieldName(), $data);
    }

    protected function handleScalar(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {

        if (empty($data['value'])) {
            return;
        }

        $callback = $this->getOption('callback');
        if ($callback){

            call_user_func($callback, $queryBuilder, $alias, $field, $data);

        } else {
            if (isset($data['type']) && $data['type'] == BooleanType::TYPE_NO) {
                $this->applyWhere($queryBuilder, $alias.'.'.$field.' < :dateStart OR '.$alias.'.'.$field.' > :dateEnd');
            } else {
                $this->applyWhere($queryBuilder, sprintf('%s.%s >= :%s', $alias, $field, 'dateStart'));
                $this->applyWhere($queryBuilder, sprintf('%s.%s <= :%s', $alias, $field, 'dateEnd'));
            }

            $queryBuilder->setParameter('dateStart', $data['value']->dateStart);
            $queryBuilder->setParameter('dateEnd', $data['value']->dateEnd);
        }


    }

    protected function association(ProxyQueryInterface $queryBuilder, $data)
    {

        $types = array(
            ClassMetadataInfo::ONE_TO_ONE,
            ClassMetadataInfo::ONE_TO_MANY,
            ClassMetadataInfo::MANY_TO_MANY,
            ClassMetadataInfo::MANY_TO_ONE,
        );

        if (!in_array($this->getOption('mapping_type'), $types)) {
            throw new \RunTimeException('Invalid mapping type');
        }

        if (!$this->getOption('field_name')) {
            throw new \RunTimeException('please provide a field_name options');
        }

        return array($this->getOption('alias', $queryBuilder->getRootAlias()), false);

    }

    public function getDefaultOptions()
    {
        return array(
            'mapping_type' => ClassMetadataInfo::MANY_TO_ONE,
            'field_name'   => false,
            'field_type'   => 'shtumi_daterange',
            'field_options' => array(),
            'operator_type' => 'sonata_type_boolean',
            'operator_options' => array(),
            'callback'      => null,
        );
    }

    public function getRenderSettings()
    {
        $options = $this->getFieldOptions();

        return array('sonata_type_filter_default', array(
            'field_type'    => $this->getFieldType(),
            'field_options' => $options,
            'operator_type' => $this->getOption('operator_type'),
            'operator_options' => $this->getOption('operator_options'),
            'label'         => $this->getLabel()
        ));
    }
}
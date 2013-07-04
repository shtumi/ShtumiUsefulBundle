<?php

/**
 * Description of DateRangeType
 *
 * @author shtumi
 */

namespace Shtumi\UsefulBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToStringTransformer;
use Shtumi\UsefulBundle\Form\DataTransformer\DateRangeToValueTransformer;

use Shtumi\UsefulBundle\Model\DateRange;

class DateRangeType extends AbstractType
{
    private $date_format;
    private $default_interval;
    private $container;

    public function __construct($container, $parameters)
    {
        $this->date_format      = $parameters['date_format'];
        $this->default_interval = $parameters['default_interval'];
        $this->container        = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'default' => null,
            'compound' => false,
        ));
    }

    public function getParent()
    {
        return 'field';
    }

    public function getName()
    {
        return 'shtumi_daterange';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if (!isset($options['default'])) {
            if ($options['required']){
                $dateRange = new DateRange($this->date_format);
                $dateRange->createToDate(new \DateTime, $this->default_interval);
            } else {
                $dateRange = null;
            }

        } else {
            $dateRange = $options['default'];
        }

        $options['default'] = $dateRange;

        $builder->addViewTransformer(new DateRangeToValueTransformer(
            $this->date_format
        ));

        $builder->setData($options['default']);

        // Datepicker date format
        $searches = array('d', 'm', 'y', 'Y');
        $replaces = array('dd', 'mm', 'yy', 'yyyy');

        $datepicker_format = str_replace($searches, $replaces, $this->date_format);

        $builder->setAttribute('datepicker_date_format', $datepicker_format);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['datepicker_date_format'] = $form->getConfig()->getAttribute('datepicker_date_format');
        $view->vars['locale'] = $this->container->get('request')->getLocale();
    }
}

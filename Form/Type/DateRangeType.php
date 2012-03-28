<?php

/**
 * Description of DateRangeType
 *
 * @author shtumi
 */

namespace Shtumi\UsefulBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

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

    public function getDefaultOptions(array $options)
    {

        if (!isset($options['default'])){
            $dateRange = new DateRange($this->date_format);
        }
        else {
            $dateRange = $options['default'];
        }

        return array(
            'default' => $dateRange
        );
    }

    public function getParent(array $options)
    {
        return 'field';
    }

    public function getName()
    {
        return 'daterange';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder->appendClientTransformer(new DateRangeToValueTransformer(
            $options['default']->date_format
        ));

        $builder->setData($options['default']);

        // Datepicker date format
        $searches = array('d', 'm', 'y', 'Y');
        $replaces = array('dd', 'mm', 'yy', 'yyyy');
        $datepicker_format = str_replace($searches, $replaces, $options['default']->date_format);

        $builder->setAttribute('datepicker_date_format', $datepicker_format);
    }

    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('datepicker_date_format', $form->getAttribute('datepicker_date_format'));
        $view->set('locale', $this->container->get('request')->getLocale());

    }


}

?>

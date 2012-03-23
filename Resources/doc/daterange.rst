ShtumiUsefulBundle - make typical things easier

DateRange
=========

.. image:: https://github.com/shtumi/ShtumiUsefulBundle/raw/master/Resources/doc/images/daterange.png

Configuration
-------------

You should add settings in config:

// app/config/config.yml

::

    shtumi_useful:
        date_range:
            date_format: d/m/Y
            default_interval: P30D

- **date_format** - date format in drop down calendar. Default: ``d/m/Y``
- **default_interval** - argument for DateInterval::__construct(). Default ``P30D`` - 30 days

Usage
=====

DateRange
---------

This form type operate with objects ``Shtumi\UsefulBundle\Model\DateRange``.

There are three ways to create DateRange object:

1. By using ``shtumi_daterange`` service
****************************************

::

    // public function createToDate($dateEnd="now", $date_format = null, $date_interval=null)

    $dateRange1 = $this->container->get('shtumi_daterange')->createToDate();

    $dateRange2 = $this->container->get('shtumi_daterange')->createToDate(new \DateTime('2012-01-11'), 'd/m/Y', 'P14D');


2. By creating new object
*************************

::

    use Shtumi\UsefulBundle\Model\DateRange;

    ...

    $date_format = 'Y-m-d';

    $dateRange3 = new DateRange($date_format);

    $dateRange3->createToDate(new \DateTime(), 'P3D');

3. By parsing formatted string
******************************

::

    use Shtumi\UsefulBundle\Model\DateRange;

    ...

    $dateRange4 = new DateRange('m/d/Y');

    $dateRange4->parseData('03/27/2012 - 04/05/2012');

--------------------------------

DateRange has two main public properties ``dateStart`` and ``dateEnd``:

::

    echo $dateRange->dateEnd->format('d.m.Y'); //23.03.2012

You can convert DateRange object into string:

::

    $x = (string)$dateRange3; // 2012-03-20 - 2012-03-23



Form type
=========

::

    $formBuilder
        ->add('point1', "shtumi_daterange", array('required'=>false
                                                , 'default'=>$dateRange1))


If you use ``shtumi_daterange`` in your own bundle with your own twig templates, you should load
`JQuery <http://jquery.com>`_.

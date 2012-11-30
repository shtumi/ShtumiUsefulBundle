ShtumiUsefulBundle - make typical things easier

Dependent filtered entity
=========================

.. image:: https://github.com/shtumi/ShtumiUsefulBundle/raw/master/Resources/doc/images/dependent_filtered_entity.png


Configuration
-------------

You should configure relationship between master and dependent fields for each pair:

*In this example master entity - AcmeDemoBundle:Country, dependent - AcmeDemoBundle:Region*

// app/config/config.yml

::

    shtumi_useful :
        dependent_filtered_entities:
            region_by_country:
                class: AcmeDemoBundle:Region
                parent_property: country
                property: title
                role: ROLE_USER
                no_result_msg: 'No regions found for that country'
                order_property: title
                order_direction: ASC

- **class** - Doctrine dependent entity.
- **role** - User role to use form type. Default: ``IS_AUTHENTICATED_ANONYMOUSLY``. It needs for security reason.
- **parent_property** - property that contains master entity with ManyToOne relationship
- **property** - Property that will used as text in select box. Default: ``title``
- **no_result_msg** - text that will be used for select box where nothing dependent entities were found for selected master entity. Default ``No results were found``. You can translate this message in ``messages.{locale}.php`` files.
- **order_property** - property that used for ordering dependent entities in selec box. Default: ``id``
- **order_direction** - You can use:
   - ``ASC`` - (**default**)
   - ``DESC`` - LIKE '%value'


Usage
=====

Simple usage
------------

Master and dependent fields should be in form together.

::

    $formBuilder
        ->add('country', 'entity', array('class'      => 'AcmeDemoBundle:Country'
                                       , 'required'   => true
                                       , 'empty_value'=> '== Choose country =='))
        ->add('region', 'shtumi_dependent_filtered_entity'
                    , array('entity_alias' => 'region_by_country'
                          , 'empty_value'=> '== Choose region =='
                          , 'parent_field'=>'country'))

- **parent_field** - name of master field in your FormBuilder



Mutiple levels
--------------

You can configure multiple dependent filters:

// app/config/config.yml

::

    shtumi_useful :
        dependent_filtered_entities:
            region_by_country:
                class: AcmeDemoBundle:Region
                parent_property: country
                property: title
                role: ROLE_USER
                no_result_msg: 'No regions found for that country'
                order_property: title
                order_direction: ASC
            town_by_region:
                class: AcmeDemoBundle:Town
                parent_property: region
                property: title
                role: ROLE_USER
                no_result_msg: 'No towns found for that region'
                order_property: title
                order_direction: ASC

::

    $formBuilder
        ->add('country', 'entity', array('class'      => 'AcmeDemoBundle:Country'
                                       , 'required'   => true
                                       , 'empty_value'=> '== Choose country =='))
        ->add('region', 'shtumi_dependent_filtered_entity'
                    , array('entity_alias' => 'region_by_country'
                          , 'empty_value'=> '== Choose region =='
                          , 'parent_field'=>'country'))
        ->add('town', 'shtumi_dependent_filtered_entity'
                    , array('entity_alias' => 'town_by_region'
                          , 'empty_value'=> '== Choose town =='
                          , 'parent_field'=>'region'))

- **parent_field** - name of master field in your FormBuilder

You should load `JQuery <http://jquery.com>`_ to your views.
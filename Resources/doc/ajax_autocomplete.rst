ShtumiUsefulBundle - make typical things easier

Ajax autocomplete type
======================

.. image:: https://github.com/shtumi/ShtumiUsefulBundle/raw/master/Resources/doc/images/ajax_autocomplete.png


Configuration
-------------

You should configure each autocomplete filter:

// app/config/config.yml

::

    shtumi_useful:
        autocomplete_entities:
            users:
                class: AcmeDemoBundle:User
                role: ROLE_ADMIN
                property: email

            products:
                class: AcmeDemoBundle:Product
                role: ROLE_ADMIN
                search: contains

- **class** - Doctrine model.
- **role** - User role to use form type. Default: *IS_AUTHENTICATED_ANONYMOUSLY*. It needs for security reason.
- **property** - Property that will be prompted by autocomplete. Default: *title*.
- **search** - LIKE format to get autocomplete values. You can use:
   - *begins_with* - LIKE 'value%' (**default**)
   - *ends_with* - LIKE '%value'
   - *contains*  - LIKE '%value%'
- **case_insensitive** - Whether or not matching should be case sensitive or not

Usage
=====

Simple usage
------------

::

    $formBuilder
        ->add('product', 'shtumi_ajax_autocomplete', array('entity_alias'=>'products'));

If you use ``shtumi_ajax_autocomplete`` in your own bundle with your own twig templates, you should load
`JQuery <http://jquery.com>`_.


Usage as SonataAdminBundle filter
---------------------------------

*Entity Sale should contain ManyToOne field user linked to AcmeDemoBundle:User*

::

    //src/Acme/DemoBundle/Admin/SaleAdmin.php
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('user', 'shtumi_ajax_autocomplete', array('entity_alias'=>'users'))
        ;
    }


====================
Filter with callback
====================

Useful when entity doesn't contain autocomplete field as ManyToOne

::

    //src/Acme/DemoBundle/Admin/UserAdmin.php
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ...
            ->add('email', 'shtumi_ajax_autocomplete', array('entity_alias'=>'users',
            'callback' =>
            function ($queryBuilder, $alias, $field, $data) {
                if (!$data['value']) {
                    return;
                }
                if ($data['type']== 1){ //1 - no, 0 - yes
                    $eq = " != ";
                } else {
                    $eq = " = ";
                }

                $queryBuilder
                    ->andWhere($alias . '.email' . $eq . ':value1')
                    ->setParameter('value1', $data['value']);
            }))
        ;
    }

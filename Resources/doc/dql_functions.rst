ShtumiUsefulBundle - make typical things easier

DQL functions
=============

Configuration
-------------

To use provided by ShtumiUsefulBundle DQL functions you should configure your doctrine:

// app/config/config.yml

::

    doctrine:
        ...
        orm:
            entity_managers:
                default:
                    dql:
                        datetime_functions:
                            datediff: Shtumi\UsefulBundle\DQL\DateDiff
                        numeric_functions:
                            if: Shtumi\UsefulBundle\DQL\IfStatement
                            ifnull: Shtumi\UsefulBundle\DQL\IfNull
                            round: Shtumi\UsefulBundle\DQL\Round


Usage
=====

IF
------

::

    $em->createQuery('SELECT IF(s.a>s.b, s.a, s.b)
                      FROM AcmeDemoBundle:Sale s')

IFNULL
------

::

    $em->createQuery('SELECT IFNULL(SUM(s.amount), 0)
                      FROM AcmeDemoBundle:Sale s')

ROUND
-----

::

    $em->createQuery('SELECT ROUND(s.amount, 2)
                      FROM AcmeDemoBundle:Sale s
                      WHERE s.id=1')


DATEDIFF
--------
Returns days between two dates

::

    $em->createQuery('SELECT DATEDIFF(s.date_shipped, s.date_ordered)
                      FROM AcmeDemoBundle:Sale s
                      WHERE s.id=1')

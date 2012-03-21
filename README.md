ShtumiUsefulBundle - make typical things easier
===============================================

ShtumiUsefulBundle provides some useful things that needed almost in every project. It's:

**Form types**:
* Ajax Autocomplete form type (useful when you operate with thousands and hundred thousands records (for instance: users))
* Dependent filtered form type (useful when you need operate dependent entities in one form (for instance: countries/regions))
* Date range form type (allows you select date range with JS calendar and take valid DateRange object)

**DQL extra functions**:
* IFNULL
* ROUND
* DATE_DIFF

You can use Ajax autocomplete form type as a filter type with [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle) 



## Installation
  
### Add the following lines to your  `deps` file and then run `php bin/vendors install`:    

```
[ShtumiUsefulBundle]
    git=https://github.com/shtumi/ShtumiUsefulBundle.git
    target=bundles/Shtumi/UsefulBundle

[GregwarFormBundle]
    git=git://github.com/Gregwar/FormBundle.git
    target=/bundles/Gregwar/FormBundle
    
[SonataDoctrineORMAdminBundle]
    git=http://github.com/sonata-project/SonataDoctrineORMAdminBundle.git
    target=/bundles/Sonata/DoctrineORMAdminBundle
```

You also should install [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle) and all dependencies for it.

### Add ShtumiUsefulBundle to your application kernel
```
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Shtumi\UsefulBundle\ShtumiUsefulBundle(),
	    new Gregwar\FormBundle\GregwarFormBundle(),
	    new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),            
            // ...
        );
    }
```
### Register the ShtumiUsefulBundle namespace
```
    // app/autoload.php
    $loader->registerNamespaces(array(
        'Shtumi'            => __DIR__.'/../vendor/bundles',
	'Gregwar'           => __DIR__.'/../vendor/bundles',
	'Sonata'            => __DIR__.'/../vendor/bundles',	
        // your other namespaces
    ));
 ```   
### Import routes

// app/config/config.yml
```
shtumi_useful:
    resource: '@ShtumiUsefulBundle/Resources/config/routing.xml'
```

### Update your configuration app/config/config.yml

// app/config/config.yml
```
shtumi_useful:
    autocomplete_entities:
        campaigns:
            class: AcmeDemoBundle:Campaign
            role: ROLE_ADMIN
            property: title
            search: contains

        customers:
            class: AcmeDemoBundle:USER
            property: email

    dependent_filtered_entities:
        region_by_country:
            class: AcmeDemoBundle:Region
            parent_property: country
            role: ROLE_USER
            no_result_msg: 'No regions found for that country'
            order_property: title
```    

### Add custom DQL functions in your Doctrine configuration
// app/config/config.yml
```
doctrine:
    ...
    orm:
        entity_managers:
            default:
                dql:
                    datetime_functions:
                        datediff: Shtumi\UsefulBundle\DQL\DateDiff
                    numeric_functions:
                        ifnull: Shtumi\UsefulBundle\DQL\IfNull
                        round: Shtumi\UsefulBundle\DQL\Round
```			
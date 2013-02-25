ShtumiUsefulBundle - make typical things easier
===============================================

ShtumiUsefulBundle provides some useful things that needed almost in every project. It's:

**Form types**:

* [Ajax Autocomplete form type](https://github.com/shtumi/ShtumiUsefulBundle/blob/master/Resources/doc/ajax_autocomplete.rst) (useful when you operate with thousands and hundred thousands records [for instance: users])

* [Dependent filtered form type](https://github.com/shtumi/ShtumiUsefulBundle/blob/master/Resources/doc/dependent_filtered_entity.rst) (useful when you need operate dependent entities in one form (for instance: countries/regions))

* [Date range form type](https://github.com/shtumi/ShtumiUsefulBundle/blob/master/Resources/doc/daterange.rst) (allows you select date range with JS calendar and take valid DateRange object)

**[DQL extra functions](https://github.com/shtumi/ShtumiUsefulBundle/blob/master/Resources/doc/dql_functions.rst)**:

* IF

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
```

For Symfony 2.0 please use branch 2.0 of ShtumiUsefulBundle:

```
[ShtumiUsefulBundle]
    git=https://github.com/shtumi/ShtumiUsefulBundle.git
    target=bundles/Shtumi/UsefulBundle
    version=origin/2.0
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
            // ...
        );
    }
```
### Register the ShtumiUsefulBundle namespace
```
    // app/autoload.php
    $loader->registerNamespaces(array(
        'Shtumi'            => __DIR__.'/../vendor/bundles',
        // your other namespaces
    ));
```
### Import routes

// app/config/routing.yml

```
shtumi_useful:
    resource: '@ShtumiUsefulBundle/Resources/config/routing.xml'
```

### Update your configuration

#### Add form theming to twig
```
twig:
    ...
    form:
        resources:
            - ShtumiUsefulBundle::fields.html.twig
```

Update your configuration in accordance with [using ShtumiUsefulBundle things](https://github.com/shtumi/ShtumiUsefulBundle/blob/master/Resources/doc/index.rst)

### Load jQuery to your views
```
    <script src="http://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript"></script>
```

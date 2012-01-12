# RocketPack - package management for RocketSled

RocketPack is a Git and SemVer based dependency
management system for PHP 5.3 applications running
on *nix using the RocketSled framework.

See also: http://semver.org/

## Goals

Working with a minimalist framework like https://github.com/iaindooley/RocketSled,
one is encouraged to create lots of very small and focused modules that do one thing
and do it well.

The only problem is that this then leads to problems managing installation and 
versionig of all these modules.

RocketPack provides a simple way for packages to register dependencies on particular
versions of various modules and have them automatically installed by cloning them from
a remote git repository.

## Usage

### Versioning your packages with RocketPack

In order to use RocketPack, you should add a file called ```rocketpack.config.php``` to your package.

The first line registers your package and states the current version, using the SemVer 
major/minor/patch format in an array as such:

```php
rocketpack\Install::package('YourPackage',array(0,1,0));
```

That would register YourPackage at v0.1. You can then add one or more dependencies:

```php
rocketpack\Dependencies::register(function()
{
    rocketpack\Dependency::forPackage('YourPackage')
    ->add('PackageWeAreDependentOn',array(0,1,0))
    ->add('AnotherPackageWeAreDependentOn',array(1,1,0))
    ->verify();
});
```

That would state that YourPackage is dependent on ```PackageWeAreDependentOn``` version v0.1 and ```AnotherPackageWeAreDependentOn``` v1.1.

This is what the complete ```rocketpack.config.php``` file would look like:

```php
<?php
    rocketpack\Install::package('YourPackage',array(0,1,0));

    rocketpack\Dependencies::register(function()
    {
        rocketpack\Dependency::forPackage('YourPackage')
        ->add('PackageWeAreDependentOn',array(0,1,0))
        ->add('AnotherPackageWeAreDependentOn',array(1,1,0))
        ->verify();
    });
```

NB: If you would like to leave the cloned out repository on head, use: ```array(0,0,0)``` for the version.

### Installing packages with RocketPack

RocketPack works with https://github.com/iaindooley/RocketSled - see the RocketSled README file for more details on installation and configuration.

Now imagine that you created a package that requires Args, PluSQL, Murphy and Fragmentify, all at version v0.1. You would add the following 
to your ```rocketpack.config.php``` file:

```php
<?php
    rocketpack\Install::package('YourPackage',array(0,1,0));
    
    rocketpack\Dependencies::register(function()
    {
        rocketpack\Dependency::forPackage('YourPackage')
        ->add('PluSQL',array(0,1,0))
        ->add('Murphy',array(0,1,0))
        ->add('Args',array(0,1,0))
        ->add('Fragmentify',array(0,1,0))
        ->verify();
    });
```

When you want to install your package, rather than having to download and install each dependency individually, you can simply install your package in the packages directory of your RocketSled 
install and run:

```
php index.php RocketPack
```

It will download all your dependencies and check them out to the correct versions. If you ever upgrade to a new version of a package, you can re-run
RocketPack to make sure you haven't broken any dependencies.

RocketPack currently doesn't "recursively" install packages, so if a newly downloaded package has dependencies they will not be retrieved. You should
keep running RocketPack until you get no output.

In future it will keep running until it's gotten all dependencies.


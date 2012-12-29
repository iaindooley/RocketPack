# RocketPack - package management for RocketSled

RocketPack is a Git based dependency
management system for PHP 5.3 applications running
on *nix using the RocketSled framework.

We recommend: http://semver.org/

## Goals

Working with a minimalist framework like https://github.com/iaindooley/RocketSled,
one is encouraged to create lots of very small and focused modules that do one thing
and do it well.

The only problem is that this then leads to problems managing installation and 
versioning of all these modules.

RocketPack provides a simple way for packages to register dependencies on particular
versions of various modules and have them automatically installed by cloning them from
a remote git repository.

## Usage

### Versioning your packages with RocketPack

In order to use RocketPack, you should add a file called ```rocketpack.config.php``` to your package.

The first line registers your package and states the current version, using the SemVer 
major/minor/patch format in an array as such:

NB: you can also just use a version string as well if you don't use semver, however this will mean
that RocketPack is unable to ignore patch and minor version mismatches

```php
RocketPack\Install::package('https://github.com/iaindooley/RocketPack',array(0,1,0));
```

That would register YourPackage at v0.1. You can then add one or more dependencies:

```php
RocketPack\Dependencies::register(function()
{
    RocketPack\Dependency::forPackage('https://github.com/iaindooley/RocketPack')
    ->add('https://github.com/iaindooley/Args',array(0,1,0))
    ->add('https://github.com/iaindooley/Murphy',array(0,1,1))
    ->verify();
});
```

That would state that RocketPack is dependent on ```Args``` version v0.1 and ```Murphy``` v1.1.

You can use the into() method to change the install location:

```php
RocketPack\Dependencies::register(function()
{
    RocketPack\Dependency::forPackage('https://github.com/iaindooley/RocketPack')
    ->into(RocketSled::rs_dir())
    ->add('https://github.com/iaindooley/Args',array(0,1,0))
    ->add('https://github.com/iaindooley/Murphy',array(0,1,1))
    ->verify();
});
```

If the directory you install into differs from RocketSled::userland_dir(), the 
version will be appended to the install directory. If you are managing 
dependencies which are not RocketSled compatible packages (ie. they do not
conform to the default RocketSled autoload naming convention or don't have
rs.config.php files in them so you need to require an autoload implementation)
you can manage these using RocketPack by doing following.

Firstly, create a PHP file in your package 

This is what the complete ```rocketpack.config.php``` file would look like:

```php
<?php
    RocketPack\Install::package('https://github.com/iaindooley/RocketPack',array(0,1,0));

    RocketPack\Dependencies::register(function()
    {
        RocketPack\Dependency::forPackage('https://github.com/iaindooley/RocketPack')
        ->into(RocketSled::rs_dir())
        ->add('https://github.com/iaindooley/Args',array(0,1,0))
        ->add('https://github.com/iaindooley/Murphy',array(0,1,1))
        ->into(RocketSled::userland_dir())
        ->add('your.server.com/path/to/Repo.git',array(0,1,0))
        ->add('your.server.com/path/to/Other.git',array(0,1,1))
        ->into(RocketSled::lib_dir())
        ->add('https://github.com/SwiftMailer','v1.2.2')
        ->into(RocketSled::root_dir())
        ->add('https://github.com/jquery','v1.2.2')
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
    RocketPack\Install::package('YourPackage',array(0,1,0));
    
    RocketPack\Dependencies::register(function()
    {
        RocketPack\Dependency::forPackage('http://github.com/yourname/YourPackage')
        ->add('https://github.com/iaindooley/PluSQL',array(0,1,0))
        ->add('https://github.com/iaindooley/Murphy',array(0,1,0))
        ->add('https://github.com/iaindooley/Args',array(0,1,0))
        ->add('https://github.com/iaindooley/Fragmentify',array(0,1,0))
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

Note that the package paths just have to be any repo that can be executed with:

```
git clone
```

So you can use this to manage repos that you have setup on your own remote git servers, too.

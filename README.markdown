# RocketPack - dependency manager for PHP 5.3+

RocketPack is a dependency management system for PHP 5.3+ applications.

Currently it only supports dependencies using git on *nix but we could add
additional version control systems in later on.

## Goals

 * Work like git submodules without the headaches
 * Allow installation in multiple directories (eg. for shared codebases)
 * Config files written in PHP (for hackability and general configurability)
 * Framework and (repository) language agnostic

## Usage

### Versioning your packages with RocketPack

Create a rocketpack config file (called, for example, rocketpack.config.php) that looks like this:

```php
<?php
    RocketPack\Dependency::register(function()
    {
        RocketPack\Dependency::into(dirname(__FILE__).'../')
        ->add('https://github.com/iaindooley/Args')
        ->add('https://github.com/iaindooley/Murphy','b5ad86d1193eb7efbe7be3ba26ff5b4e5a0476d4')
        ;
    });
```

The first argument to the add() method can be anything that can be passed to git clone.

For example you can change the name of the install directory:

```php
    RocketPack\Dependency::register(function()
    {
        RocketPack\Dependency::into(dirname(__FILE__).'../')
        ->add('https://github.com/iaindooley/Args')
        ->add('https://github.com/iaindooley/Murphy','b5ad86d1193eb7efbe7be3ba26ff5b4e5a0476d4')
        ->add('git@bitbucket.com:company/lowercaserepo CamelCaseRepo')
        ;
    });
```

The second argument can be anything that can be passed to git checkout. For example:

```php
    RocketPack\Dependency::register(function()
    {
        RocketPack\Dependency::into(dirname(__FILE__).'../')
        ->add('https://github.com/iaindooley/Args')
        ->add('https://github.com/iaindooley/Murphy','b5ad86d1193eb7efbe7be3ba26ff5b4e5a0476d4')
        ->add('git@bitbucket.com:company/lowercaserepo CamelCaseRepo','-b some-branch origin/some-branch)
        ;
    });
```

The md5 hash of the second argument will be appended to the directory name which means that
if you have packages with conflicting versions they will both be installed (see section below on
conflicts).

You can also chain calls to into() and install things into a bunch of different directories:

```php
RocketPack\Dependency::register(function()
{
    RocketPack\Dependency::into(dirname(__FILE__).'../')
    ->add('https://github.com/iaindooley/Args')
    ->add('https://github.com/iaindooley/Murphy','b5ad86d1193eb7efbe7be3ba26ff5b4e5a0476d4')
    ->add('git@bitbucket.com:company/lowercaserepo CamelCaseRepo')
    ->into($some_other_dir)
    ->add('https://github.com/iaindooley/CrazyHorse')
    ;
});
```

### Installing packages with RocketPack

In order to execute your RocketPack config files, just pass an array of full file paths
into the RocketPack::install() method:

```php
$packs = array(
    'rocketpack.config.php',
);

RocketPack::install($packs);
```

NB: the file paths will just be passed into require_once() so you need to make sure
you pass in the full file paths.

## A complete example

Create a file called rocketpack.config.php that looks like this:

```php
<?php
    RocketPack\Dependency::register(function()
    {
        RocketPack\Dependency::into(dirname(__FILE__))
        ->add('https://github.com/jquery/jquery','1.10.2')
        ;
    });
```
Create a file called install.php in the same directory that looks like this:

```php
<?php
    require_once('RocketPack/autoload.php');
    $packs = array(
        'rocketpack.config.php',
    );
    
    RocketPack::install($packs);
```

### Recursion

If any of the packages you install contain a file called rocketpack.config.php the install
process will be run again recursively.

### Conflicts

If any packages try to install conflicting versions of the same package in the same
directory, then you will be able to tell because you will have two different directories
for the same package but with different version hashes appended.

Each package that RocketPack installs will contain a file called .rocketpack with the
exact repo and version string used to install it which you can use to find out which
packages are installing which versions and resolve the conflict (for example by 
bumping the dependency/version number on one of your packages or figuring out a way
to run both at once).

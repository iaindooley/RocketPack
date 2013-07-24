<?php
    spl_autoload_register(function($class)
    {
        if($class == 'RocketPack')
            require_once(dirname(__FILE__).'/rocket_pack.class.php');
        else if(($class == 'RocketPack\Dependency')||
                ($class == '\RocketPack\Dependency'))
            require_once(dirname(__FILE__).'/dependency.class.php');
    });

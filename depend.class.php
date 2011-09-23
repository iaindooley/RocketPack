<?php
    namespace rocketpack;
    
    class Include
    {
        public static function package($name,$version)
        {
            $version = new Version($version);
            
            if(defined($name))
                $version->compare(constant($name));
            else
                die('not installed');
        }
    }

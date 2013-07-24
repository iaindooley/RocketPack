<?php
    use RocketPack\Dependency;
    
    class RocketPack
    {
        public static function install($packs)
        {
            foreach($packs as $fname)
                require($fname);
            
            foreach(Dependency::registered() as $dep)
                $dep();
            
            if($packs = Dependency::newPacks())
            {
                Dependency::reset();
                RocketPack::install($packs);
            }
        }
    }

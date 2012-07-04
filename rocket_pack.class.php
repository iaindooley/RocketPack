<?php
    use rocketpack\Dependencies;
    use RocketSled\filteredPackages;
    use RocketSled\endsWith;
    
    class RocketPack implements RocketSled\Runnable
    {
        public function run()
        {
            $packs = RocketSled\filteredPackages(function($input)
            {
                return RocketSled\endsWith($input,'rocketpack.config.php');
            });
            
            $dependencies = array();
            
            foreach($packs as $fname)
                require($fname);
            
            foreach(Dependencies::registered() as $dep)
                $dep();
        }
    }

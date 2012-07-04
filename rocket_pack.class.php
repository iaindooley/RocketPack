<?php
    use RocketPack\Dependencies;
    use RocketSled\filteredPackages;
    use RocketSled\endsWith;
    
    class RocketPack implements RocketSled\Runnable
    {
        public function run()
        {
            $packs = RocketSled\filteredPackages(function($input)
            {
                return RocketSled\endsWith($input,'RocketPack.config.php');
            });
            
            $dependencies = array();
            
            foreach($packs as $fname)
                require($fname);
            
            foreach(Dependencies::registered() as $dep)
                $dep();
        }
    }

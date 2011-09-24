<?php
    use rocketsled\filteredPackages;
    use rocketsled\endsWith;
    
    class CheckDependencies implements rocketsled\Runnable
    {
        public function run()
        {
            $packs = rocketsled\filteredPackages(function($input)
            {
                return rocketsled\endsWith($input,'rocketpack.config.php');
            });
            
            $dependencies = array();
            
            foreach($packs as $fname)
                require($fname);
            
            foreach(Dependencies::registered() as $dep)
                $dep();
        }
    }

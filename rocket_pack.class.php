<?php
    use RocketPack\Dependencies;
    
    class RocketPack implements RocketSled\Runnable
    {
        public function run()
        {
            $packs = RocketSled::filteredPackages(function($input)
            {
                return RocketSled::endsWith($input,'rocketpack.config.php');
            });
            
            $dependencies = array();
            
            foreach($packs as $fname)
                require($fname);
            
            foreach(Dependencies::registered() as $dep)
                $dep();
        }
        
        public static function autoload($directory,$repo,$version,$bootstrap_file)
        {
            require_once(self::installPath($directory,$repo,$version).'/'.$bootstrap_file);
        }

        public static function installPath($directory,$repo,$version)
        {
            return $directory.'/'.self::packageName($directory,$repo,$version);
        }

        public static function packageName($directory,$repo,$version)
        {
            $name = str_replace('.git','',basename($repo));

            if($directory != RocketSled::userland_dir())
            {
                if(is_array($version))
                    //just use major/minor and ignore patch
                    $package_version = $version[0].'.'.$version[1];
                else
                    $package_version = $version;

                $name .= '_'.$package_version;
            }
            
            return $name;
        }
    }

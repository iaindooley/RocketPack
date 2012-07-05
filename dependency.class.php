<?php
    namespace RocketPack;
    use Exception;
    
    class Dependency
    {
        private $versions;
        private $exceptions;
        private $for_package;

        private function __construct($for_package)
        {
            $this->versions = array();
            $this->exceptions = array();
            $this->for_package = $for_package;
        }
        
        public static function forPackage($repo)
        {
            $dep = new Dependency($repo);
            return $dep;
        }
        
        public function add($repo,$version)
        {
            self::processDependency($this,$repo,$version);
            return $this;
        }
        
        private static function processDependency(Dependency $dep,$repo,$version)
        {
            $to_compare = new Version($repo,$version);

            if(!Install::ed($repo))
            {
                if(is_array($version))
                {
                    if(end($version) === 0)
                        array_pop($version);
                    if(end($version) === 0)
                        array_pop($version);

                    $version_string = implode('.',$version);
                }

                else
                    $version_string = $version;

                echo shell_exec(self::parseInstallCommand($dep->for_package,$repo));
                $name = str_replace('.git','',basename($repo));
                
                if($version_string != '0')
                {
                    echo shell_exec('cd '.escapeshellarg(realpath(PACKAGES_DIR)).'/'.escapeshellarg($name).' && /usr/bin/env git checkout '.$version_string);
                }

                // The path to the rocketpack.config.php - it will exist if this
                // is a native RocketPack package
                $rocketpack_config = realpath(PACKAGES_DIR).'/'.$name.'/rocketpack.config.php';
                if(file_exists($rocketpack_config))
                {
                    require($rocketpack_config);
                    echo 'New package: '.$name.' installed. Re-run php index.php RocketPack'.PHP_EOL;
                }
                else
                {
                    echo 'New package: '.$name.' installed.'.PHP_EOL;

                }
            }

            try
            {
                $to_compare->compare(Install::version($repo));
            }
            
            catch(VersionMismatchException $exc)
            {
                $dep->exceptions[] = $exc;
            }
        }
        
        public function verify(VerifyRules $rules = NULL)
        {
            if(!$rules)
                $rules = new VerifyRules();

            if($out = $rules->verify($this->exceptions))
            {
                echo ' ----- For package: '.$this->for_package.PHP_EOL;
                echo $out.PHP_EOL;
            }
        }
        
        public static function parseInstallCommand($for_package,$repo)
        {
            return 'cd '.escapeshellarg(realpath(PACKAGES_DIR)).' && /usr/bin/env git clone '.escapeshellarg(trim($repo));
        }
    }
    
    class DependencyInstallException extends Exception{}

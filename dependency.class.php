<?php
    namespace rocketpack;
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
        
        public static function forPackage($name)
        {
            $dep = new Dependency($name);
            return $dep;
        }
        
        public function add($name,$version)
        {
            self::processDependency($this,$name,$version);
            return $this;
        }
        
        private static function processDependency(Dependency $dep,$name,$version)
        {
            $version = new Version($name,$version);

            if(Install::ed($name))
            {
                shell_exec(self::parseInstallCommand($name));
                self::requireConfig($name);
            }

            else
                throw new DependencyInstallException('Unable to install '.$name.' using '.self::parseInstallCommand($name));

            try
            {
                $version->compare(Install::version($name));
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
        
        public static function parseInstallCommand()
        {
        }

        public static function requireConfig($name)
        {
        }
    }
    
    class DependencyInstallException extends Exception{}

<?php
    namespace rocketpack;
    use Exception;
    
    class Dependency
    {
        private $versions;
        private $exceptions;

        private function __construct()
        {
            $this->versions = array();
            $this->exceptions = array();
        }
        
        public static function add($name,$version)
        {
            $dep = new Dependency();
            self::processDependency($dep,$name,$version);
            return $dep;
        }
        
        public function also($name,$version)
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

            $rules->verify($this->exceptions);
        }
        
        public static function parseInstallCommand()
        {
        }

        public static function requireConfig($name)
        {
        }
    }
    
    class DependencyInstallException extends Exception{}

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
            $to_compare = new Version($name,$version);

            if(!Install::ed($name))
            {
                $version_string = implode('.',$version);
                echo shell_exec(self::parseInstallCommand($dep->for_package,$name));
                
                if($version_string != '0.0.0')
                    echo shell_exec('cd '.escapeshellarg(realpath(PACKAGES_DIR)).'/'.strtolower($name).' && /usr/bin/env git checkout '.$version_string);

                require(realpath(PACKAGES_DIR).'/'.strtolower($name).'/rocketpack.config.php');
                echo 'New package: '.$name.' installed. Re-run php index.php CheckDependencies'.PHP_EOL;
            }

            try
            {
                $to_compare->compare(Install::version($name));
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
        
        public static function parseInstallCommand($for_package,$name)
        {
            //get the latest plist.txt
            echo shell_exec('cd '.escapeshellarg(realpath(PACKAGES_DIR)).'/rocketpack/ && /usr/bin/env git pull');
            
            $cmd = 'cd '.escapeshellarg(realpath(PACKAGES_DIR)).' && /usr/bin/env git clone ';
            $repos = array_filter(file(PACKAGES_DIR.'/rocketpack/plist.txt',FILE_IGNORE_NEW_LINES),
            function($arg) use($name)
            {
                $ret = FALSE;
                
                if(\rocketsled\endsWith($arg,$name))
                    $ret = $arg;
                
                return $ret;
            });
            
            if(count($repos) > 1)
            {
                echo 'When attempting to install: '.$name.' for package: '.$for_package.' got more than one repository in plist.txt: '.PHP_EOL;
                echo implode(PHP_EOL,$repos).PHP_EOL;
                echo 'Sort that shit out yo!'.PHP_EOL;
                echo 'Exiting due to errors'.PHP_EOL;
                exit(1);
            }
            
            else if(!count($repos))
            {
                echo 'When attempting to install: '.$name.' for package: '.$for_package.' got no matching repositories in plist.txt: '.PHP_EOL;
                echo 'Sort that shit out yo!'.PHP_EOL;
                echo 'Exiting due to errors'.PHP_EOL;
                exit(1);
            }
            
            $cmd .= trim(current($repos)).' '.strtolower($name);
            return $cmd;
        }
    }
    
    class DependencyInstallException extends Exception{}

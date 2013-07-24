<?php
    namespace RocketPack;
    use Closure;

    class Dependency
    {
        private $install_directory;
        private $register;
        private $new_packs;
        private static $instance = NULL;

        private function __construct()
        {
            $this->register = array();
            $this->new_packs = array();
            $this->install_directory = NULL;
        }
        
        public static function newPacks()
        {
            return self::instance()->new_packs;
        }

        public static function register(Closure $func)
        {
            self::instance()->register[] = $func;
        }

        public function registered()
        {
            return self::instance()->register;
        }

        public static function reset()
        {
            self::$instance = NULL;
        }

        private static function instance()
        {
            if(self::$instance === NULL)
                self::$instance = new Dependency();

            return self::$instance;
        }

        public static function into($directory)
        {
            self::instance()->install_directory = $directory;
            return self::instance();
        }
        
        public function add($repo,$version = NULL)
        {
            if($pack = self::processDependency($this,$repo,$version))
                $this->new_packs[] = $pack;

            return $this;
        }
        
        private static function processDependency(Dependency $dep,$repo,$version)
        {
            $ret = FALSE;
            exec(self::parseInstallCommand($dep->install_directory,$repo),$output,$ret);
            $gitoutput = $output[0];
            //Cloning into 'Args'...
            if(preg_match("/Cloning into '(.*)'.../",$gitoutput,$matches))
                $installed_in = $matches[1];
            //fatal: destination path 'Args' already exists and is not an empty directory.
            else if(preg_match("/fatal: destination path '(.*)' already exists and is not an empty directory./",$gitoutput,$matches))
                $installed_in = $matches[1];

            if($version !== NULL)
            {
                $md5 = md5($version);
                echo shell_exec('cd '.escapeshellarg(realpath($dep->install_directory)).'/'.escapeshellarg($installed_in).' && /usr/bin/env git checkout '.$version);
                echo shell_exec('cd '.escapeshellarg(realpath($dep->install_directory)).'/ && mv '.escapeshellarg($installed_in).' '.escapeshellarg($installed_in.'-'.$md5));
                $installed_in = $installed_in.'-'.$md5;
                file_put_contents(realpath($dep->install_directory).'/'.$installed_in.'/.rocketpack',$repo.PHP_EOL.$version);
            }
            
            if(file_exists(realpath($dep->install_directory).'/'.$installed_in.'/rocketpack.config.php'))
                $ret = realpath($dep->install_directory).'/'.$installed_in.'/rocketpack.config.php';
            
            return $ret;
        }
        
        public static function parseInstallCommand($install_directory,$repo)
        {
            if(!$install_directory)
                $install_directory = '.';

            $split = array_filter(explode(' ',$repo),function($to_filter)
            {
                return escapeshellarg($to_filter);
            });

            return 'cd '.escapeshellarg(realpath($install_directory)).' && /usr/bin/env git clone '.trim(implode(' ',$split)).' 2>&1';
        }
    }

<?php
    namespace rocketpack;

    class Install
    {
        private $packages;
        private static $instance = NULL;
        
        private function __construct()
        {
            $this->packages = array();
        }
        
        public static function clear()
        {
            if(self::$instance !== NULL)
                self::$instance->packages = array();
        }

        public static function package($name,$version)
        {
            if(self::$instance === NULL)
                self::$instance = new Install();
            
            self::$instance->packages[$name] = $version;
        }
        
        public static function version($name)
        {
            $ret = FALSE;

            if(self::$instance !== NULL)
                if(self::ed($name))
                    $ret = self::$instance->packages[$name];
            
            return $ret;
        }

        public static function ed($name)
        {
            $ret = FALSE;
            
            if(self::$instance !== NULL)
                $ret = isset(self::$instance->packages[$name]);
            
            return $ret;
        }
    }

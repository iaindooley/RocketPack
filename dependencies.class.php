<?php
    class Dependencies
    {
        private static $instance = NULL;
        private $register;
        
        private function __construct()
        {
            $this->register = array();
        }
        
        public static function register(Closure $func)
        {
            if(self::$instance === NULL)
                self::$instance = new Dependencies();

            self::instance()->register[] = $func;
        }
        
        public function registered()
        {

            return self::instance()->register;
        }
        
        private static function instance()
        {
            if(self::$instance === NULL)
                self::$instance = new Dependencies();
            
            return self::$instance;
        }
    }

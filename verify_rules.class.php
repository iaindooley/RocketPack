<?php 
    namespace rocketpack;

    class VerifyRules
    {
        const MAJOR = 'MinorVersionMismatchException';
        const MINOR = 'MinorVersionMismatchException';
        const PATCH = 'PatchVersionMismatchException';
        private $ignore;
        private $print_warnings;
        
        public function __construct()
        {
            $this->ignore = array();
            $this->print_warnings = TRUE;
        }
        
        public function dontPrintWarnings()
        {
            $this->print_warnings = FALSE;
            return $this;
        }
        
        public static function ignore($package_name,$type)
        {
            $rules = new VerifyRules();
            self::addRule($rules->ignore,$package_name,$type);
            return $rules;
        }
        
        public function also($package_name,$type)
        {
            self::addRule($rules->ignore,$package_name,$type);
            return $this;
        }
       
        private static function addRule(&$rules,$package_name,$type)
        {
            if(!isset($rules[$package_name]))
                $rules[$package_name] = array();
            
            $rules[$package_name][] = 'rocketpack\\'.$type;
        }
        
        public function verify($exceptions)
        {
            $warnings = array();
            $fatal    = array();
            
            foreach($exceptions as $exc)
            {
                if(isset($this->ignore[$exc->package()]))
                {
                    if(!in_array(get_class($exc),$this->ignore[$exc->package()]))
                        self::addError($exc,$warnings,$fatal);
                }
                
                else
                    self::addError($exc,$warnings,$fatal);
            }
            
            $ret = '';
            
            if(count($warnings)&&$this->print_warnings)
            {
                $ret .= 'ROCKETPACK DEPENDENCY WARNINGS: '.PHP_EOL;
                $ret .= implode(PHP_EOL,$warnings);
                $ret .= PHP_EOL;
            }
            
            if(count($fatal))
            {
                $ret .= 'ROCKETPACK DEPENDENCY FATAL ERRORS: '.PHP_EOL;
                $ret .= implode(PHP_EOL,$fatal);
                $ret .= PHP_EOL;
            }
            
            return $ret;
        }
        
        public static function addError(VersionMismatchException $exc,&$warnings,&$fatal)
        {
            if($exc instanceof MajorVersionMismatchException)
                $fatal[]    = get_class($exc).' for package '.$exc->package().': '.$exc->getMessage();
            else
                $warnings[] = get_class($exc).' for package '.$exc->package().': '.$exc->getMessage();
        }
    }

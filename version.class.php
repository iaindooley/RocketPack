<?php
    namespace RocketPack;
    use Exception;
    
    class Version
    {
        private $version;
        private $package;
        
        public function __construct($package,$version)
        {
            if(count($version) != 3)
                throw new InvalidVersionSchemaException('RocketPack version management only works with a 3 part version number, Major, Minor, Patch. You gave me: '.$version.' Check out http://semver.org/');
                
            $this->version = $version;
            $this->package = $package;
        }
        
        public function compare($version)
        {
            if(count($version) != 3)
                throw new InvalidVersionSchemaException('RocketPack version management only works with a 3 part version number, Major, Minor, Patch. You gave me: '.$version.' Check out http://semver.org/');

            if($this->version[0] != $version[0])
                throw new MajorVersionMismatchException($this->package,'Comparing '.implode('.',$this->version).' to '.implode('.',$version).' for package '.$this->package);
            if($this->version[1] != $version[1])
                throw new MinorVersionMismatchException($this->package,'Comparing '.implode('.',$this->version).' to '.implode('.',$version).' for package '.$this->package);
            if($this->version[2] != $version[2])
                throw new PatchVersionMismatchException($this->package,'Comparing '.implode('.',$this->version).' to '.implode('.',$version).' for package '.$this->package);
        }
    }

    class InvalidVersionSchemaException extends Exception{}
    class VersionMismatchException extends Exception
    {
        private $package;

        public function __construct($package,$message)
        {
            parent::__construct($message);
            $this->package = $package;
        }
        
        public function package()
        {
            return $this->package;
        }
    }
    
    class MajorVersionMismatchException extends VersionMismatchException{}
    class MinorVersionMismatchException extends VersionMismatchException{}
    class PatchVersionMismatchException extends VersionMismatchException{}

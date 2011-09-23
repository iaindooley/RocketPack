<?php
    namespace rocketpack;
    use Exception
    
    class Version
    {
        private $major;
        private $minor;
        private $patch;
        private $complete_version;
        
        public function __construct($version)
        {
            $this->complete_version = $version;
            
            list($this->major,
                 $this->minor,
                 $this->patch) = self::splitVersion($version);
        }
        
        public function compare($version)
        {
            list($major,$minor,$patch) = self::splitVersion($version);
            
            if($major != $this->major)
                throw new MajorVersionMismatchException($major.' != '.$this->major.' (comparing '.$version.' to '.$this->complete_version.')');
            if($minor != $this->minor)
                throw new MinorVersionMismatchException($minor.' != '.$this->minor.' (comparing '.$version.' to '.$this->complete_version.')');
            if($patch != $this->patch)
                throw new MinorVersionMismatchException($patch.' != '.$this->patch.' (comparing '.$version.' to '.$this->complete_version.')');
        }
        
        public static function splitVersion($version)
        {
            $split = explode('.',preg_replace('/[^0-9.]/','',$version));
            
            if(count($split) != 3)
                throw new InvalidVersionSchemaException('RocketPack version management only works with a 3 part version number, Major, Minor, Patch. You gave me: '.$version.' Check out http://semver.org/');
            return $split;
        }
    }

    class InvalidVersionSchemaException extends Exception{}
    
    class VersionMismatchException extends Exception
    {
        private $package;
        
        public function setPackage($package)
        {
            $this->package = $package;
        }
        
        public function getMessage()
        {
            return parent::getMessage().' for package: '.$this->package;
        }
    }

    class MajorVersionMismatchException extends VersionMismatchExceptionException{}
    class MinorVersionMismatchException extends VersionMismatchExceptionException{}
    class PatchVersionMismatchException extends VersionMismatchExceptionException{}

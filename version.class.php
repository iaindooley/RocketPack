<?php
    namespace rocketpack;
    use Exception;
    
    class Version
    {
        private $major;
        private $minor;
        private $patch;
        private $complete_version;
        private $package;
        
        public function __construct($package,$version)
        {
            $this->complete_version = $version;
            $this->package          = $package;
            
            list($this->major,
                 $this->minor,
                 $this->patch) = self::splitVersion($version);
        }
        
        public function compare($version)
        {
            list($major,$minor,$patch) = self::splitVersion($version);
            
            if($major != $this->major)
                throw new MajorVersionMismatchException($major.' != '.$this->major.' (comparing '.$version.' to '.$this->complete_version.' in package '.$this->package.')');
            if($minor != $this->minor)
                throw new MinorVersionMismatchException($minor.' != '.$this->minor.' (comparing '.$version.' to '.$this->complete_version.' in package '.$this->package.')');
            if($patch != $this->patch)
                throw new MinorVersionMismatchException($patch.' != '.$this->patch.' (comparing '.$version.' to '.$this->complete_version.' in package '.$this->package.')');
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
    class VersionMismatchException extends Exception{}
    class MajorVersionMismatchException extends VersionMismatchException{}
    class MinorVersionMismatchException extends VersionMismatchException{}
    class PatchVersionMismatchException extends VersionMismatchException{}

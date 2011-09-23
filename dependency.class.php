<?php
    namespace rocketpack;
    
    class Dependency
    {
        public static function package($name,$version)
        {
            $version = new Version($name,$version);

            if(defined($name))
                $version->compare(constant($name));
            else
            {
                $cmd = self::parseInstallCommand($name);
                shell_exec($cmd);

                if(defined($name))
                {
                    try
                    {
                        $version->compare(constant($name));
                    }
                    
                    catch(VersionMismatchException $exc)
                    {
                        self::$exceptions[] = $exc;
                    }
                }
            }
        }
        
        public static function parseInstallCommand()
        {
        }
    }

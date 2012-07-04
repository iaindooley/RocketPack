<?php
    namespace RocketPack;

    class TestVerifyRules implements \RocketSled\Runnable
    {
        public function run()
        {
            $version = new Version('nothing',array(1,1,1));
            $exceptions = array(new MinorVersionMismatchException('PluSQL','This is just a test'),
                                new MajorVersionMismatchException('PluSQL','This is just a test'),
                                new MajorVersionMismatchException('Murphy','This is just a test'),
                                new MajorVersionMismatchException('Args','This is just a test'),
                                new PatchVersionMismatchException('Args','This is just a test'),
                                new PatchVersionMismatchException('PluSQL','This is just a test'),
                               );
            
            VerifyRules::ignore('PluSQL',VerifyRules::MAJOR)
            ->also('Murphy',VerifyRules::MINOR)
            ->also('Args',VerifyRules::PATCH)
            ->verify($exceptions);
        }
    }

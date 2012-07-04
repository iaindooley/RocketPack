<?php
    use RocketPack\VerifyRules;

    class TestVerifyRules implements RocketSled\Runnable
    {
        public function run()
        {
            $version = new RocketPack\Version('nothing',array(1,1,1));
            $exceptions = array(new RocketPack\MinorVersionMismatchException('PluSQL','This is just a test'),
                                new RocketPack\MajorVersionMismatchException('PluSQL','This is just a test'),
                                new RocketPack\MajorVersionMismatchException('Murphy','This is just a test'),
                                new RocketPack\MajorVersionMismatchException('Args','This is just a test'),
                                new RocketPack\PatchVersionMismatchException('Args','This is just a test'),
                                new RocketPack\PatchVersionMismatchException('PluSQL','This is just a test'),
                               );
            
            VerifyRules::ignore('PluSQL',VerifyRules::MAJOR)
            ->also('Murphy',VerifyRules::MINOR)
            ->also('Args',VerifyRules::PATCH)
            ->verify($exceptions);
        }
    }

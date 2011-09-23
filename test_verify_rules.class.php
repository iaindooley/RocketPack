<?php
    use rocketpack\VerifyRules;

    class TestVerifyRules implements rocketsled\Runnable
    {
        public function run()
        {
            $version = new rocketpack\Version('nothing','1.1.1');
            $exceptions = array(new rocketpack\MinorVersionMismatchException('PluSQL','This is just a test'),
                                new rocketpack\MajorVersionMismatchException('PluSQL','This is just a test'),
                                new rocketpack\MajorVersionMismatchException('Murphy','This is just a test'),
                                new rocketpack\MajorVersionMismatchException('Args','This is just a test'),
                                new rocketpack\PatchVersionMismatchException('Args','This is just a test'),
                                new rocketpack\PatchVersionMismatchException('PluSQL','This is just a test'),
                               );
            
            VerifyRules::ignore('PluSQL',VerifyRules::MAJOR)
            ->also('Murphy',VerifyRules::MINOR)
            ->also('Args',VerifyRules::PATCH)
            ->verify($exceptions);
        }
    }

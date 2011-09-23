<?php
    use rocketpack\VerifyRules;

    class TestDependency implements rocketsled\Runnable
    {
        public function run()
        {
            //NO ERRORS
            rocketpack\Install::package('PluSQL',array(0,0,1));
            rocketpack\Install::package('Args',array(0,0,1));
            rocketpack\Install::package('Murphy',array(0,0,1));

            rocketpack\Dependency::forPackage('MyApp')
            ->add('PluSQL',array(0,0,1))
            ->add('Args',array(0,0,1))
            ->add('Murphy',array(0,0,1))
            ->verify(VerifyRules::ignore('PluSQL',VerifyRules::PATCH)
                     ->also('Args',VerifyRules::MINOR)
                     ->also('Murphy',VerifyRules::MAJOR)
            );
            //WITH ERRORS
            rocketpack\Install::package('PluSQL',array(0,1,2));
            rocketpack\Install::package('Args',array(1,1,2));
            rocketpack\Install::package('Murphy',array(1,1,2));

            rocketpack\Dependency::forPackage('MyApp')
            ->add('PluSQL',array(0,0,1))
            ->add('Args',array(0,0,1))
            ->add('Murphy',array(0,0,1))
            ->verify(VerifyRules::ignore('PluSQL',VerifyRules::PATCH)
                     ->also('Args',VerifyRules::MINOR)
                     ->also('Murphy',VerifyRules::MAJOR)
            );
        }
    }

<?php
    namespace RocketPack;

    class TestDependency implements \RocketSled\Runnable
    {
        public function run()
        {
            //NO ERRORS
            Install::package('PluSQL',array(0,0,1));
            Install::package('Args',array(0,0,1));
            Install::package('Murphy',array(0,0,1));

            Dependency::forPackage('MyApp')
            ->add('PluSQL',array(0,0,1))
            ->add('Args',array(0,0,1))
            ->add('Murphy',array(0,0,1))
            ->verify(VerifyRules::ignore('PluSQL',VerifyRules::PATCH)
                     ->also('Args',VerifyRules::MINOR)
                     ->also('Murphy',VerifyRules::MAJOR)
            );
            //WITH ERRORS
            Install::package('PluSQL',array(0,1,2));
            Install::package('Args',array(1,1,2));
            Install::package('Murphy',array(1,1,2));

            Dependency::forPackage('MyApp')
            ->add('PluSQL',array(0,0,1))
            ->add('Args',array(0,0,1))
            ->add('Murphy',array(0,0,1))
            ->verify(VerifyRules::ignore('PluSQL',VerifyRules::PATCH)
                     ->also('Args',VerifyRules::MINOR)
                     ->also('Murphy',VerifyRules::MAJOR)
            );
        }
    }

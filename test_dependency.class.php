<?php
    class TestDependency implements rocketsled\Runnable
    {
        public function run()
        {
            rocketpack\Dependency::package('PluSQL','v0.0.1');
        }
    }

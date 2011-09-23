<?php
    class TestDepend implements rocketsled\Runnable
    {
        public function run()
        {
            rocketpack\Depend::package('PluSQL','v0.0.1');
        }
    }

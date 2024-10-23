<?php

namespace DarkTec\Helpers;

class Blade {

    private static ?\Lexdubyna\Blade\Blade $blade = null;

    public static function getBlade($components) {

        if (self::$blade == null) {
            self::$blade = new \Lexdubyna\Blade\Blade('views', 'cache');
            self::$blade->compiler()->components($components);
        }

        return self::$blade;
    }
}
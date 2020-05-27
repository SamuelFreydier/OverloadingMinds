<?php

namespace App\Src;

class Autoloader
{
    public static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload(string $class) {
        $namespace = explode('\\', $class);
        $class=implode('/', $namespace);
        require_once $_SERVER['DOCUMENT_ROOT'].'/'.$class.'.php';
    }

}
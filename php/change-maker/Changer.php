<?php
require_once('Usdollar.php');
require_once('Yen.php');

class Changer
{
    private function __construct() { }

    public static function factory(string $cType)
    {
        switch( strtolower($cType) ) {
            case 'usd':
                return new Usdollar;
            case 'yen':
                return new Yen;
            default:
                throw new Exception('Currency '.$cType.' not supported');
        }
    }
}

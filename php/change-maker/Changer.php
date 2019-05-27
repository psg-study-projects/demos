<?php
//require_once('Currency.php');
require_once('Usdollar.php');
require_once('Yen.php');

// This can just be currency class, and we initiaize it (via injection?) with a json array currency 'map'
class Changer
{

    private function __construct()
    {
    }

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

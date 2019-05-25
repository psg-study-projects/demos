<?php
require_once('Currency.php');
/*
TODO:
    [ ] unit test the classes
        ~ add up the change to verify it matches total
*/

// This can just be currency class, and we initiaize it (via injection?) with a json array currency 'map'
class Usdollar extends Currency
{
    protected static $_baseunit = 'cents';
    protected static $_denominations = [ // simulates DB data, %TODO: move to main(), init via constructor
        1 => [
            'name' => 'Penny',
            'is_available' => true,
        ],
        5 => [
            'name' => 'Nickel',
            'is_available' => true,
        ],
        10 => [
            'name' => 'Dime',
            'is_available' => true,
        ],
        25 => [
            'name' => 'Quarter',
            'is_available' => true,
        ],
        50 => [
            'name' => 'Half-Dollar Coin',
            'is_available' => false,
        ],
        100 => [
            'name' => 'One Dollar Note',
            'is_available' => true,
        ],
        200 => [
            'name' => 'Two Dollar Note',
            'is_available' => false,
        ],
        500 => [
            'name' => 'Five Dollar Note',
            'is_available' => true,
        ],
        1000 => [
            'name' => 'Ten Dollar Note',
            'is_available' => true,
        ],
        2000 => [
            'name' => 'Twenty Dollar Note',
            'is_available' => true,
        ],
        5000 => [
            'name' => 'Fifty Dollar Note',
            'is_available' => true,
        ],
        10000 => [
            'name' => 'One-Hundred Dollar Note',
            'is_available' => true,
        ],
    ];

    public function __construct()
    {
        $this->denominations = self::$_denominations;
        uksort($this->denominations, function($a,$b) {
            return $b - $a;
        });
        //print_r($this->denominations);
        //die;
    }


    // %TODO: use IoC, with only one class Currency, and inject a dollar (or yen, etc) 'object' with 
    // an implemenation of this function as well as the denomination 'map'
    // still need a 'type' common to the actual denominations so we can use that generic 'type' in the constructor of the Currency class
    // Class restructure
    // Currency -> Changer
    // UsDollar/Yen -> injectable objects of abstract type (or interface?) 'Currency'
    public static function renderNiceAmount(int $val) : string
    {
        $dollars = floor($val/100);
        $cents = sprintf("%02d", $val%100);
        return '$'.$dollars.'.'.$cents;
    }

    public function makeChange(int $delta) : array
    {
        echo 'Recieved '.self::renderNiceAmount($delta).'...'."\n";
        $d = $delta; // init
        foreach ($this->denominations as $v => $o) {
            if ( !$o['is_available'] ) {
                continue; // skip if not avail.
            }
            $d = floor( $delta / $v);
            echo "$d - $v";
            if ($d > 0 ) {
                echo '  ('.$d.' '.$this->renderDenomName($v).')';
            }
            echo "\n";
            $delta = $delta - $d*$v;
        }
        return [];
    }
}

// test
$usd = new Usdollar();
$usd->makeChange( 107 );
echo "\n---------------- \n";
$usd->makeChange( 17 );
echo "\n---------------- \n";
$usd->makeChange( 997 );
echo "\n---------------- \n";
$usd->makeChange( 2323 );
echo "\n---------------- \n";
$usd->makeChange( 10072 );
echo "\n---------------- \n";
$usd->makeChange( 15729 );
echo "\n---------------- \n";
$usd->makeChange( 7382 );

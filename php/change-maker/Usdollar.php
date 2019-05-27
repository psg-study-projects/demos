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
    }


    public static function renderNiceAmount(int $val) : string
    {
        $dollars = floor($val/100);
        $cents = sprintf("%02d", $val%100);
        return '$'.$dollars.'.'.$cents;
    }
}

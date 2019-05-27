<?php
require_once('Currency.php');

class Yen extends Currency
{
    protected static $_baseunit = 'yen';
    protected static $_denominations = [ // simulates DB data, %TODO: move to main(), init via constructor
        1 => [
            'name' => '1 Yen Coin',
            'is_available' => true,
        ],
        5 => [
            'name' => '5 Yen Coin',
            'is_available' => true,
        ],
        10 => [
            'name' => '10 Yen Coin',
            'is_available' => true,
        ],
        50 => [
            'name' => '50 Yen Coin',
            'is_available' => true,
        ],
        100 => [
            'name' => '100 Yen Coin',
            'is_available' => true,
        ],
        500 => [
            'name' => '500 Yen Coin',
            'is_available' => true,
        ],
        1000 => [
            'name' => 'Thousand Yen Note',
            'is_available' => true,
        ],
        2000 => [
            'name' => 'Two Thousand Yen Note',
            'is_available' => false,
        ],
        5000 => [
            'name' => 'Five Thousand Yen Note',
            'is_available' => true,
        ],
        10000 => [
            'name' => 'Ten Thousand Yen Note',
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
        return 	html_entity_decode('&#165;').$val;
    }

}


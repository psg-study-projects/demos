<?php
abstract class Currency 
{
    protected $denominations; // initialized in child class

    // $val is the 'key' for the denomination (value in baseunit)
    public function renderDenomName(int $val) : string
    {
        return array_key_exists($val, $this->denominations) ? $this->denominations[$val]['name'] : 'N/A';
    }

    // display the currency in human-readable form instead of base units
    abstract public static function renderNiceAmount(int $val) : string;

    // 'amounts' must be in base units, integer values only
    abstract public function makeChange(int $totalCost, int $amountProvided) : array;
}

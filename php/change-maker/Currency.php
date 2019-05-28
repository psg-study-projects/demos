<?php

// Abstract base class with default implentations where approprate
abstract class Currency 
{
    protected $denominations; // initialized in child class

    public function renderDenomName(int $val) : string
    {
        // $val is the 'key' for the denomination (value in baseunit)
        return array_key_exists($val, $this->denominations) ? $this->denominations[$val]['name'] : 'N/A';
    }

    // Display the currency in human-readable form instead of base units
    abstract public static function renderNiceAmount(int $val) : string;

    // Default implementation for the 'workhorse' function which computes the change. This 
    // code is independent of currency type.
    //    ~ 'amounts' must be in base units, integer values only
    public function makeChange(int $totalCost, int $amountProvided) : array
    {
        $delta = $amountProvided - $totalCost;
        if ($delta < 0) {
            throw new Exception('Not enough cash provided to cover cost...');
        }
        $results = [
            'change' => null,
            'delta' => static::renderNiceAmount($delta),
            'amount_provided' => static::renderNiceAmount($amountProvided),
            'total_cost' => static::renderNiceAmount($totalCost),
        ];
        $change = [];
        $d = $delta; // init
        foreach ($this->denominations as $v => $o) {
            if ( !$o['is_available'] ) {
                continue; // skip if not avail.
            }
            $d = floor( $delta / $v);
            if ($d > 0 ) {
                $change[] = [
                    'denomination' => $this->renderDenomName($v),
                    'sub-amount' => static::renderNiceAmount($d*$v),
                    'qty' => $d,
                    'base_unit' => $v,

                ];
            }
            $delta = $delta - $d*$v; // remainder for next iteration
        }
        $results['change'] = $change;
        return $results;
    }
}

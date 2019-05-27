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
            //echo "$d - $v";
            if ($d > 0 ) {
                //echo '  ('.$d.' '.$this->renderDenomName($v).')';
                $change[] = [
                    'denomination' => $this->renderDenomName($v),
                    'sub-amount' => static::renderNiceAmount($d*$v),
                    'qty' => $d,
                    'base_unit' => $v,

                ];
            }
            //echo "\n";
            $delta = $delta - $d*$v;
        }
        $results['change'] = $change;
        return $results;
    }
}

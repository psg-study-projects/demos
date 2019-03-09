<?php
namespace App\Models;

interface Ownable {

    // model is/is not owend by other models only
    public function isOwnedBy(Model $obj=null) : bool;

    public static function getOwnerSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array;

}

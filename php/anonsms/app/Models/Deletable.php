<?php
namespace App\Models;

interface Deletable {

    public function isDeletable() : bool;

}

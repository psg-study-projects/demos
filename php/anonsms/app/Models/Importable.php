<?php
namespace App\Models;

interface Importable {

    public static function doCsvImport(string $filepath, array $headerMap);

}

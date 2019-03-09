<?php
namespace App\Models;
use PsgcLaravelPackages\Utils\SmartEnum;

class GenderTypeEnum extends SmartEnum implements Selectable {

    const MALE      = 'male';
    const FEMALE    = 'female';

    public static $keymap = [
        self::MALE    => 'Male',
        self::FEMALE  => 'Female',
    ];

}

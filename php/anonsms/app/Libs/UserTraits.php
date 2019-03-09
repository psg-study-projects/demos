<?php
namespace App\Libs;

// %TODO: put in composer or other package manager
trait UserTraits {

    public static function getUser($userId = null)
    {
        if (!empty($userId)) {
            $user = \User::select('id', 'name', 'email')->find($userId);
        } elseif (\Auth::guest()) {
            $user = null;
        } else {
            $user = \Auth::user();
            unset($user->password);
        }

        return $user;
    }

}

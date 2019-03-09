<?php
namespace Tests;

use App\Models\User;

class TestHelpers
{
    // %TODO: implement $withRole
    public static function getUser(string $withRole=null)
    {
        $user = null; // init

        if ( is_null($withRole) ) {
            // non-admin
            $users = User::whereDoesntHave('roles', function($q1) {
                $q1->where('name','admin');
            })->get();
            $user = $users[0];
        } else {
            $user = User::whereHas('roles', function($q2) use($withRole) {
                $q2->where('name',$withRole);
            })->first();
        }

        return $user;
    }

    public static function getAdminUser()
    {
        $user = \App\Models\User::whereHas('roles', function($q1) {
            $q1->where('name','admin');
        })->firstOrFail();
        return $user;
    }


}


<?php
namespace App\Http\Middleware;

use PsgcLaravelPackages\AccessControl\AccessManager;
//use App\Models\User;
//use App\Models\Widget;
//use App\Models\Ownable;

class CheckSiteRole extends AccessManager
{
    protected function setSuperadminRoles()
    {
        $this->_superadminRoles = ['admin'];
    }

    protected function accessMatrix() {

        // keyed by route names...

        return [

            'site.dashboard.*'=>[
                'manager'=>true,
                'owner'=>true,
                'mts'=>true,
            ],

            'site.messages.*'=>[
                'manager'=>true,
                'owner'=>true,
                'mts'=>true,
            ],

            // ---Accounts ---

            'site.accounts.*'=>[
                'manager'=>true,
                'owner'=>true,
                'mts'=>true,
            ],
            'site.accounts.show'=>[
                //'manager'=>true,
                'owner'=>false,
                //'mts'=>false,
            ],


            // --- Widgets ---

            'site.widgets.index'=>[
                'manager'=>true,
                'owner'=>true,
                'mts'=>true,
            ],
            'site.widgets.show'=>[
                'manager'=>function($user,$routeParams,$queryParams) { // delegate as closure
                    // %FIXME %TODO: delegate this callback/lambda to model class (?)
                    $widget = Widget::findOrFail($routeParams['widget']);
                    if ($widget instanceof Ownable) {
                        $isAllowed = $widget->isOwnedBy();
                        //$isAllowed = ($widget->owner_id == $this->_sessionUser->id);
                    }
                    return $isAllowed;
                },
                'owner'=>function($user,$routeParams,$queryParams) { // delegate as closure
                    // %FIXME %TODO: delegate this callback/lambda to model class (?)
                    $widget = Widget::findOrFail($routeParams['widget']);
                    if ($widget instanceof Ownable) {
                        $isAllowed = $widget->isOwnedBy();
                        //$isAllowed = ($widget->owner_id == $this->_sessionUser->id);
                    }
                    return $isAllowed;
                },
            ],
        ];
    }


}

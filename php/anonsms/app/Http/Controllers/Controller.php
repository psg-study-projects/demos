<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Libs\CssManager;
use App\Libs\JsManager;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $_assetMgr = null; // object
    protected $_php2jsVars = [];

    public function __construct()
    {
        $this->_assetMgr = new \Assetmanager();

        $this->_php2jsVars['autocomplete_min_length'] = 1; // default, can be ridden by individual controllers

        \View::share('g_user', User::getUser());
        \View::share('g_php2jsVars', $this->_php2jsVars); // may be overridden in child
        \View::share('g_body_bg', 'default');

        // Don't set g_module here as it will be called by Formbuilder controller and overwite other modules

        return;
    }
}

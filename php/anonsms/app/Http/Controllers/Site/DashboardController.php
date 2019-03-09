<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use App\Http\Controllers\SiteController;
use App\Models\Usercustom;

class DashboardController extends SiteController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr::registerJsLibs([
        ]);
        $this->_assetMgr::registerJsInlines([
        ]);
        $this->_assetMgr::registerCssInlines([
            '/css/app/main/styles.css',
            //asset('css/app.css'),
        ]);
    }
  
    public function show(Request $request)
    {
        $sessionUser = \Auth::user();

        return \View::make('site.dashboard.show', [
        ]);
    }
}

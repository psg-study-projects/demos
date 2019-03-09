<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use App\Http\Controllers\SiteController;
use App\Libs\DatatableUtils\TableContainer;
use App\Models\User;

class DashboardController extends SiteController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr::registerJsLibs([
            '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
            '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js ',
        ]);
        $this->_assetMgr::registerJsInlines([
            '/js/app/common/inline/initDataTables.js',
        ]);
        $this->_assetMgr::registerCssInlines([
            '/css/app/main/styles.css',
            //asset('css/app.css'),
        ]);
    }
  
    public function show(Request $request)
    {
        $sessionUser = \Auth::user();

        $dtC_availableUsers = new TableContainer( 'users', '\App\Models\User', [
            //['colName'=>'username'],
            [
                'colName'=>'username', // colName -> column name in DB, not displayed
                'op'=>'link_to_route',
                'route'=>'site.chat.show',
                'resourceIdCol'=>'username', // column value to use for route param if applicable
            ],
        ]);

        $filters = [
            'topic_id' => $sessionUser->topic->id ?? null,
        ];
        $this->_php2jsVars['datatables'] = [
            'available_users'=>[
                'filters'=>$filters,
                'columns'=>$dtC_availableUsers->columns(),
                'meta'=>$dtC_availableUsers->meta(),
            ],
        ];
        \View::share('g_php2jsVars',$this->_php2jsVars);

        return \View::make('site.dashboard.show', [
            'session_user'=>$sessionUser,
        ]);
    }
}

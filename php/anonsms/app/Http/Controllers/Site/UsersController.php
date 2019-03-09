<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use DB;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\SiteController;
use App\Libs\DatatableUtils\TableContainer;

use App\Models\User;

class UsersController extends SiteController
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
            '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css',
            '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css',
            '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
            '/css/app/main/styles.css',
        ]);
    }
  
    public function index(Request $request)
    {
        // Assumes AJAX (Api-ish)

        $paging = ['offset'=>$request->input('start',0),'length'=>$request->input('length',10)];
        $filters =  $request->input('filters',[]);
        $search = $request->input('search',[]);

        $sorting = [
            'value' => $request->columns[$request->order[0]['column']]['name'],
            'direction' => $request->order[0]['dir']
        ];

        $recordsTotal    = User::collector()->getCount();
        $recordsFiltered = User::collector()->getCount($filters,$search);
        $records         = User::collector()->getList($filters,$search,$paging,$sorting);

        // Apply any post-adaptors (decorators)...  
        $options = $request->input('options',[]); 
        $records = TableContainer::renderColumnVals( $records, $request->meta);

        $response = ['is_ok'=>1, 'records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];

        return \Response::json($response);
    }

    /*
    public function show(Request $request, $slug)
    {
        $obj = User::where('slug',$slug)->firstOrFail();

        $dtC_stubmatters = new TableContainer( 'stubmatters', '\App\Models\Stubmatter', [
            [
                'colName'=>'guid', // colName -> column name in DB, not displayed
                'op'=>'link_to_route',
                'route'=>'site.stubmatters.show',
                'resourceIdCol'=>'slug', // column value to use for route param if applicable
            ],
            ['colName'=>'mname'],
            ['colName'=>'stubclient_id'],
            ['colName'=>'mnumber'],
            ['colName'=>'created_at'],
        ]);

        $this->_php2jsVars['datatables'] = [
            'stubmatters'=>[
                'filters'=>['stubclient_id'=>$obj->id],
                'columns'=>$dtC_stubmatters->columns(), // needed by JS at time of page render (before AJAX)
                'meta'=>$dtC_stubmatters->meta(), // can filters go here?
            ],
        ];
        \View::share('g_php2jsVars',$this->_php2jsVars);

        return \View::make('site.stubclients.show', [ 'obj'=>$obj ]);
    }
     */


}

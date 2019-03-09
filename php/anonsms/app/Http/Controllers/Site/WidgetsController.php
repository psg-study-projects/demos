<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use App\Http\Controllers\SiteController;
use App\Libs\DatatableUtils\TableContainer;
use App\Models\Widget;

class WidgetsController extends SiteController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr::registerJsLibs([
          '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
          '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js ',
          //'/js/app/common/libs/nlFormUtils.js',
        ]);
        $this->_assetMgr::registerJsInlines([
            //'/js/app/common/inline/initDataTables.js',
        ]);
        $this->_assetMgr::registerCssInlines([
            '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css',
            '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css',
            '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
            //'/css/app/main/styles.css',
        ]);

    }
  
    // Uses PsgcLaravelPackages\DatatableUtils\TableContainer to setup DataTables. For example
    // of DataTables w/o using this package see Site/AccountsController
    public function index(Request $request)
    {
        $dtC_widgets = new TableContainer( 'widgets', '\App\Models\Widget', [
            ['colName'=>'wname'],
            [
                'colName'=>'guid', // colName -> column name in DB, not displayed
                'op'=>'link_to_route',
                'route'=>'site.widgets.show',
                'resourceIdCol'=>'guid', // column value to use for route param if applicable
            ],
            [
                'colName'=>'account_id', // colName -> column name in DB, not displayed
                'op'=>'link_to_route',
                'route'=>'site.accounts.show',
                'resourceIdCol'=>'account.guid', // column value to use for route param if applicable
            ],
            ['colName'=>'wstate'],
            ['colName'=>'slug'],
            ['colName'=>'created_at'],
        ]);

        if (!\Request::ajax()) {

            $this->_php2jsVars['datatables'] = [
                'widgets'=>[
                    //'filters'=>[],
                    'columns'=>$dtC_widgets->columns(), // needed by JS at time of page render (before AJAX)
                    'meta'=>$dtC_widgets->meta(), // can filters go here?
                ],
            ];
            \View::share('g_php2jsVars',$this->_php2jsVars);

            return \View::make('site.widgets.index', [
            ]);

        } else {

            // Datatable format

            //$filters = $request->input('filters',[]);

            $paging = ['offset'=>$request->input('start',0),'length'=>$request->input('length',10)];
            $filters =  $request->input('filters',[]);
            $search = $request->input('search',[]);

            $sorting = null;
            /*
            $sorting = [
                'value' => $request->columns[$request->order[0]['column']]['name'],
                'direction' => $request->order[0]['dir']
            ];
             */

            $recordsTotal    = Widget::collector()->getCount();
            $recordsFiltered = Widget::collector()->getCount($filters,$search);
            $records         = Widget::collector()->getList($filters,$search,$paging,$sorting);

            // Apply any post-adaptors (decorators)...  
            $options = $request->input('options',[]); 
            //$records = $dtC->renderColumnVals($records); // set rendering for special fields such as links, FKs, etc
            $records = TableContainer::renderColumnVals( $records, $request->meta);

            $response = ['is_ok'=>1, 'records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];

            return \Response::json($response);

        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($slug)
    {
        //
    }

    public function edit($slug)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use DB;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\SiteController;
use App\Libs\DatatableUtils\TableContainer;

use App\Models\HbmClient;
use App\Models\HbmMatter;

class HbmclientsController extends SiteController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr->registerJsLibs([
            '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
            '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js ',
        ]);
        $this->_assetMgr->registerJsInlines([
            '/js/app/common/inline/initDataTables.js',
        ]);
        $this->_assetMgr->registerCssInlines([
            '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css',
            '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
            '/css/app/main/styles.css',
        ]);
    }
  
    public function index(Request $request)
    {
        // Assumes AJAX (Api-ish)

        if (!\Request::ajax()) {
            $dtC_hbmclients = new TableContainer( 'hbmclients', '\App\Models\HbmClient', [
                ['colName'=>'CLIENT_NAME'],
                ['colName'=>'CLIENT_CODE'],
                ['colName'=>'CLIENT_NUMBER'],
                ['colName'=>'STATUS_CODE'],
            ]);

            $this->_php2jsVars['datatables'] = [
                'hbmclients'=>[
                    'columns'=>$dtC_hbmclients->columns(),
                    'meta'=>$dtC_hbmclients->meta(),
                ],
            ];
    
            /*
            $searchAttrs = $request->only('cname','mname','clinum');
            $isSearch = $this->_php2jsVars['clientmatters']['is_search'] = count($searchAttrs) ? 1 : 0;
             */
            \View::share('g_php2jsVars',$this->_php2jsVars);
    
            //$filteredMatters = $isSearch ? $this->search($searchAttrs) : [];
    
            return \View::make('site.hbmclients.index', [
                //'filtered_matters' => $filteredMatters,
                'search_attrs' => [], //$searchAttrs,
            ]);

        } else {

            $paging = ['offset'=>$request->input('start',0),'length'=>$request->input('length',10)];
            $filters =  $request->input('filters',[]);
            $search = $request->input('search',[]);
    
            $sorting = [
                'value' => $request->columns[$request->order[0]['column']]['name'],
                'direction' => $request->order[0]['dir']
            ];
    
            $withs = [ 'matters', 'employees' ];
            $recordsTotal    = HbmClient::collector('cmsopen')->getCount();
            $recordsFiltered = HbmClient::collector('cmsopen')->getCount($filters,$search);
            //$records         = HbmClient::collector('cmsopen')->getList($filters,$search,$paging,$sorting,$withs);
            $records         = HbmClient::collector('cmsopen')->getList($filters,$search,$paging,$sorting);
    
            // Apply any post-adaptors (decorators)...  
            $options = $request->input('options',[]); 
            $records = TableContainer::renderColumnVals( $records, $request->meta);
    
            $response = ['is_ok'=>1, 'records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];
    
            return \Response::json($response);
        }
    }

    public function show(Request $request, $slug)
    {
        $obj = HbmClient::where('slug',$slug)->firstOrFail();

        $dtC_hbmmatters = new TableContainer( 'hbmmatters', '\App\Models\HbmMatter', [
            [
                'colName'=>'guid', // colName -> column name in DB, not displayed
                'op'=>'link_to_route',
                'route'=>'site.hbmmatters.show',
                'resourceIdCol'=>'slug', // column value to use for route param if applicable
            ],
            ['colName'=>'mname'],
            ['colName'=>'hbmclient_id'],
            ['colName'=>'mnumber'],
            ['colName'=>'created_at'],
        ]);

        $this->_php2jsVars['datatables'] = [
            'hbmmatters'=>[
                'filters'=>['hbmclient_id'=>$obj->id],
                'columns'=>$dtC_hbmmatters->columns(), // needed by JS at time of page render (before AJAX)
                'meta'=>$dtC_hbmmatters->meta(), // can filters go here?
            ],
        ];
        \View::share('g_php2jsVars',$this->_php2jsVars);

        return \View::make('site.hbmclients.show', [ 'obj'=>$obj ]);
    }

    public function match(Request $request)
    {
        $term = $request->input('term',null);

        $collection = HbmClient::where('CLIENT_NUMBER', 'like', $term.'%')
                                ->orWhere('CLIENT_NAME', 'like', $term.'%')
                                ->get();

        return \Response::json( $collection->map( function($item,$key) {
                return [
                    'id' => $item->CLIENT_UNO,
                    'value' => $item->CLIENT_NUMBER,
                    'label' => $item->CLIENT_NUMBER.' ('.$item->CLIENT_NAME.')',
                    'attrs' => [
                        'cname' => $item->CLIENT_NAME,
                    ],
                ];
        }) );
    }

}
                /*
                [
                    'colName'=>'guid', // colName -> column name in DB, not displayed
                    'op'=>'link_to_route',
                    'route'=>'site.hbmclients.show',
                    'resourceIdCol'=>'slug', // column value to use for route param if applicable
                ],
                [
                    'colName'=>'number_of_matters',
                    'op'=>'virtual_column',
                ],
                ['colName'=>'created_at'],
                 */

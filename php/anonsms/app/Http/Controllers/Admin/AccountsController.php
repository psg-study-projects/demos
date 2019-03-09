<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Libs\DatatableUtils\TableContainer;
use App\Models\Account;
use PsgcLaravelPackages\Utils\ViewHelpers;

class AccountsController extends AdminController
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_assetMgr::registerJsLibs([
        ]);
        $this->_assetMgr::registerJsInlines([
            '/js/app/common/inline/initDataTables.js',
        ]);
        $this->_assetMgr::registerCssInlines([
            //'/css/app/main/styles.css',
        ]);
    }

    // -------------------------------------------------------
    // %%% REST-ful Controller Methods
    // -------------------------------------------------------

    public function index(Request $request)
    {

        if (!\Request::ajax()) {

            $dtC = new TableContainer( 'accounts', '\App\Models\Account', [
                [
                    'colName'=>'guid', // colName -> column name in DB, not displayed
                    'op'=>'link_to_route',
                    'route'=>'admin.accounts.show',
                    'resourceIdCol'=>'guid', // column value to use for route param if applicable
                ],
                ['colName'=>'slug'],
                /*
                [
                    'colName'=>'owner_id',
                    'op'=>'link_to_route',
                    'route'=>'admin.organizations.show',
                    'resourceIdCol'=>'guid',
                ],
                 */
                ['colName'=>'aname'],
                ['colName'=>'accountid'],
                ['colName'=>'created_at'],
                /*
                [
                    'colName'=>'number_of_jobitems',
                    'op'=>'virtual_column',
                ],
                 */
            ]);

            $this->_php2jsVars['datatables'] = [
                'accounts'=>[
                    //'filters'=>[],
                    'columns'=>$dtC->columns(), // needed by JS at time of page render (before AJAX)
                    'meta'=>$dtC->meta(),
                ],
            ];
            \View::share('g_php2jsVars',$this->_php2jsVars);

            return \View::make('admin.base.index', [
                'tablename'=>$dtC->tablename,
                'pageheading'=>'Accounts',
                'minor_nav' => [
                    'routes' => [
                        'index' => ['name'=>'admin.accounts.index','params'=>null,'display'=>'List'],
                        'create' => ['name'=>'admin.accounts.create','params'=>null,'display'=>'Create New'],
                     ],
                 ]
            ]);

        } else {

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

            $recordsTotal    = Account::collector()->getCount();
            $recordsFiltered = Account::collector()->getCount($filters,$search);
            $records         = Account::collector()->getList($filters,$search,$paging,$sorting);

            $options = $request->input('options',[]); 
            $records = TableContainer::renderColumnVals( $records, $request->meta);

            $response = ['is_ok'=>1, 'records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];

            return \Response::json($response);

        }
    } // index()


    // %FIXME %TODO
    public function show($guid)
    {
        $obj = Account::where('guid',$guid)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find Account with guid '.$guid);
        }

        if ( \Request::ajax() ) {

            return \Response::json([ 'obj' => $obj ]);

        } else {

            $dtC_widgets = new TableContainer( 'widgets', '\App\Models\Widget', [
                [
                    'colName'=>'guid', // colName -> column name in DB, not displayed
                    'op'=>'link_to_route',
                    'route'=>'admin.widgets.show',
                    'resourceIdCol'=>'guid', // column value to use for route param if applicable
                ],
                ['colName'=>'slug'],
                ['colName'=>'wname'],
                ['colName'=>'wstate'],
                ['colName'=>'created_at'],
            ]);
            $this->_php2jsVars['datatables'] = [
                'widgets'=>[
                    'filters'=>['account_id'=>$obj->id],
                    'columns'=>$dtC_widgets->columns(),
                    'meta'=>$dtC_widgets->meta(),
                ],
            ];

            \View::share('g_php2jsVars',$this->_php2jsVars);
            return \View::make('admin.accounts.show', [
                    'obj' => $obj,
                    'tablename'=>'accounts',
                    'pageheading'=>'Account',
                    'indexroute' => 'admin.accounts.index', // %FIXME
                    'filters' => [],
                    'minor_nav' => [
                        'routes' => [
                            'index' => ['name'=>'admin.accounts.index','params'=>null,'display'=>'List'],
                            ],
                    ],
            ]);
        }
    } // show()

    public function create(Request $request)
    {
        return \View::make('admin.accounts.create', [
            'pageheading' => 'Create New Account',
            'minor_nav'=>[
                'routes' => [
                     'index' => ['name'=>'admin.accounts.index','params'=>null,'display'=>'List'],
                ],
            ],
        ]);

    } // create()


    public function edit($slug)
    {
        $obj = Account::where('slug',$slug)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find Account with slug '.$slug);
        }

        return \View::make('admin.accounts.edit', [
            'obj' => $obj,
            'pageheading' => 'Edit Account',
            'indexroute' => 'admin.accounts.index',
            'minor_nav'=>[
                'routes' => [
                     'index' => ['name'=>'admin.accounts.index','params'=>null,'display'=>'List'],
                     'show' => ['name'=>'admin.accounts.show','params'=>$obj->slug,'display'=>'Show'],
                ],
            ],
            //$data['agency'] = $agency = $obj->agency;
            //$data['filters'] = ['agency_id'=>$obj->id];
        ]);

    } // edit()


    public function store(Request $request)
    {
        $this->validate($request, Account::$vrules);

        try {

            $attrs = $request->all();
    
            $obj = \DB::transaction(function () use ($attrs) {
                $obj = Account::create($attrs);
                return $obj;
            });

        } catch (\Exception $e) {
            throw $e;
            //$messages = [$e->getMessage()];
            //$response = ['is_ok' => 0,'messages' => $messages];
        }

        return \Redirect::route('admin.accounts.show', [$obj->slug]);

    } // store()

    public function update(Request $request, $pkid)
    {
        $this->validate($request, Account::$vrules);

        try {
    
            $obj = Account::find($pkid);
            if ( empty($obj) ) {
                throw new ModelNotFoundException('Could not find Account with pkid '.$pkid);
            }
    
            $obj = \DB::transaction(function () use ($request, $obj) {
                $obj->fill($request->all());
                $obj->save();
                return $obj;
            });

        } catch (\Exception $e) {
            throw $e;
            //$messages = [$e->getMessage()];
            //$response = ['is_ok' => 0,'messages' => $messages];
        }

        return \Redirect::route('admin.accounts.show', [$obj->slug]);

    } // update()

}


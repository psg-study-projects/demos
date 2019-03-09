<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Libs\DatatableUtils\TableContainer;
use PsgcLaravelPackages\Utils\ViewHelpers;
use App\Models\User;

class UsersController extends AdminController
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

        $dtC =  new TableContainer('users');
        $dtC->addColumn(
            'fullname',
            'Name',
            function($r) { return $r->renderFullname(); }
        );
        $dtC->addColumn(
            'username',
            'Username',
            function($r) { return link_to_route('admin.users.show',$r->renderField('username'),$r->username)->toHtml(); }
        );
        $dtC->addColumn( 'email', 'Email' );
        $dtC->addColumn(
            'rolelist',
            'Roles',
            function($r) { return $r->renderRoles(); }
        );
        $dtC->addColumn(
            'is_admin',
            'Admin?',
            function($r) { return ViewHelpers::makeNiceBinary($r->hasRole('admin')); }
        );
        $dtC->addColumn( 'created_at', 'Created' );

        if (!\Request::ajax()) {

            $this->_php2jsVars['datatables'] = [
                'users'=>[
                    //'filters'=>[],
                    'colconfig'=>$dtC->columnConfig(),
                ],
            ];
            \View::share('g_php2jsVars',$this->_php2jsVars);

            return \View::make('admin.base.index', [
                'tablename'=>$dtC->tablename,
                'pageheading'=>'Users',
                'minor_nav' => [
                    'routes' => [
                        'index' => ['name'=>'admin.users.index','params'=>null,'display'=>'List'],
                        'create' => ['name'=>'admin.users.create','params'=>null,'display'=>'Create New'],
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

            $recordsTotal    = User::collector()->getCount();
            $recordsFiltered = User::collector()->getCount($filters,$search);
            $records         = User::collector()->getList($filters,$search,$paging,$sorting);

            $options = $request->input('options',[]); 
            $records = $dtC->renderColumnVals($records); // set rendering for special fields such as links, FKs, etc

            $response = ['is_ok'=>1, 'records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];

            return \Response::json($response);

        }
    } // index()


    public function show($username)
    {
        $obj = User::where('username',$username)->firstOrFail();

        // Setup minor navigation
        $extraClasses = ['delete'=>['alert-danger']];
        if ( !$obj->isDeletable() ) { // move to _show ???, or use a decorator lib of some kind??
            $extraClasses['delete'][] = 'disabled_link';
        }

// HERE %TODO
        // Setup widgets tab (list of widgets owned by this user)
        $dtcAccounts =  new TableContainer('accounts');
        $dtcAccounts->addColumn(
            'guid',
            'GUID',
            function($r) { return link_to_route('admin.accounts.show',$r->renderField('guid'),$r->slug)->toHtml(); }
        );
        $dtcAccounts->addColumn( 'accountid', 'Account ID' );
        $dtcAccounts->addColumn(
            'ownername',
            'Owner',
            function($r) { return $r->owner->username; }
        );
        $dtcAccounts->addColumn(
            'slug',
            'SLUG',
            function($r) { return link_to_route('admin.accounts.show',$r->renderField('slug'),$r->slug)->toHtml(); }
        );
        $dtcAccounts->addColumn(
            'aname', // example that does not use callback, defaults to raw column value
            'Account Name'
        );

        $this->_php2jsVars['datatables'] = [
            //'widgets'=>$dtcWidgets->columnConfig(), // key is 'resource key' ?
            'accounts'=>[
                        'filters'=>['user_id'=>$obj->id],
                        'colconfig'=>$dtcAccounts->columnConfig(), // key is 'resource key' ?
                        ],
        ];
        \View::share('g_php2jsVars',$this->_php2jsVars);

        return \View::make('admin.users.show', [
                'obj' => $obj,
                'tablename'=>'users',
                'pageheading'=>'User',
                'indexroute' => 'admin.users.index', // %FIXME
                'minor_nav' => [
                    'routes' => [
                          'index' => ['name'=>'admin.users.index','params'=>null,'display'=>'List'],
                          'edit' => ['name'=>'admin.users.edit','params'=>$obj->username,'display'=>'Edit'],
                          'loginAsUser' => ['name'=>'admin.users.loginAsUser','params'=>$obj->id,'display'=>'Login As User'],
                          'delete' => ['name'=>'admin.users.destroy','params'=>$obj->id,'display'=>'Delete','extra_classes'=>$extraClasses['delete']],
                        ],
                ],
        ]);
    } // show()

    public function create(Request $request)
    {
        return \View::make('admin.users.create', [
            'pageheading' => 'Create New User',
            'minor_nav'=>[
                'routes' => [
                     'index' => ['name'=>'admin.users.index','params'=>null,'display'=>'List'],
                ],
            ],
        ]);

    } // create()


    public function edit($username)
    {
        $obj = User::where('username',$username)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find User with username '.$username);
        }

        return \View::make('admin.users.edit', [
            'obj' => $obj,
            'pageheading' => 'Edit User',
            'indexroute' => 'admin.users.index',
            'minor_nav'=>[
                'routes' => [
                     'index' => ['name'=>'admin.users.index','params'=>null,'display'=>'List'],
                     'show' => ['name'=>'admin.users.show','params'=>$obj->username,'display'=>'Show'],
                ],
            ],
            //$data['agency'] = $agency = $obj->agency;
            //$data['filters'] = ['agency_id'=>$obj->id];
        ]);

    } // edit()


    public function store(Request $request)
    {
        $this->validate($request, User::$vrules);

        try {

            $attrs = $request->all();
    
            $obj = \DB::transaction(function () use ($attrs) {
                $obj = User::create($attrs);
                return $obj;
            });

        } catch (\Exception $e) {
            throw $e;
            //$messages = [$e->getMessage()];
            //$response = ['is_ok' => 0,'messages' => $messages];
        }

        return \Redirect::route('admin.users.show', [$obj->username]);

    } // store()

    public function update(Request $request, $pkid)
    {
        $this->validate($request, User::$vrules);

        try {
    
            $obj = User::find($pkid);
            if ( empty($obj) ) {
                throw new ModelNotFoundException('Could not find User with pkid '.$pkid);
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

        return \Redirect::route('admin.users.show', [$obj->username]);

    } // update()

    public function loginAsUser($id)
    {
        \Auth::loginUsingId($id);
        $redirectRoute = User::getRedirectRoute();
        return redirect($redirectRoute);
    }
}


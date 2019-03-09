<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\AdminController;
use App\Libs\DatatableUtils\TableContainer;
use PsgcLaravelPackages\Utils\ViewHelpers;
use App\Models\Widget;

class WidgetsController extends AdminController
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_assetMgr::registerJsLibs([ ]);
        $this->_assetMgr::registerJsInlines([
            '/js/app/common/inline/initDataTables.js',
        ]);
        $this->_assetMgr::registerCssInlines([ ]);
    }

    // -------------------------------------------------------
    // %%% REST-ful Controller Methods
    // -------------------------------------------------------

    public function index(Request $request)
    {
        if (!\Request::ajax()) {

            $dtC = new TableContainer( 'widgets', '\App\Models\Widget', [
                [
                    'colName'=>'guid', // colName -> column name in DB, not displayed
                    'op'=>'link_to_route',
                    'route'=>'admin.widgets.show',
                    'resourceIdCol'=>'guid', // column value to use for route param if applicable
                ],
                ['colName'=>'slug'],
                [
                    'colName'=>'account_id',
                    'op'=>'link_to_route',
                    'route'=>'admin.accounts.show',
                    'resourceIdCol'=>'guid',
                ],
                ['colName'=>'wname'],
                ['colName'=>'wstate'],
                ['colName'=>'created_at'],
                /*
                [
                    'colName'=>'number_of_jobitems',
                    'op'=>'virtual_column',
                ],
                 */
            ]);

            $this->_php2jsVars['datatables'] = [
                'widgets'=>[
                    //'filters'=>[],
                    'columns'=>$dtC->columns(), // needed by JS at time of page render (before AJAX)
                    'meta'=>$dtC->meta(),
                ],
            ];
            \View::share('g_php2jsVars',$this->_php2jsVars);

            return \View::make('admin.base.index', [
                'tablename'=>$dtC->tablename,
                'pageheading'=>'Widgets',
                'minor_nav' => [
                    'routes' => [
                        'index' => ['name'=>'admin.widgets.index','params'=>null,'display'=>'List'],
                        'create' => ['name'=>'admin.widgets.create','params'=>null,'display'=>'Create New'],
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

//dump('Widget Controller', $filters);
//\DB::enableQueryLog();
//dump('=> getCount()');
            $recordsTotal    = Widget::collector()->getCount();
//dump('=> getCountFiltered()');
            $recordsFiltered = Widget::collector()->getCount($filters,$search);
//dump('=> getRecordsFiltered()');
            $records         = Widget::collector()->getList($filters,$search,$paging,$sorting);

            $options = $request->input('options',[]); 
            $records = TableContainer::renderColumnVals( $records, $request->meta);

            $response = ['is_ok'=>1, 'records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];

            return \Response::json($response);

        }
    } // index()


    public function show($guid)
    {
        $obj = Widget::where('guid',$guid)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find Widget with guid '.$guid);
        }

        // Setup minor navigation
        $extraClasses = ['delete'=>['alert-danger']];
        if ( !$obj->isDeletable() ) { // move to _show ???, or use a decorator lib of some kind??
            $extraClasses['delete'][] = 'disabled_link';
        }

        return \View::make('admin.base.show', [
                'obj' => $obj,
                'tablename'=>'widgets',
                'pageheading'=>'Widget',
                'indexroute' => 'admin.widgets.index', // %FIXME
                'minor_nav' => [
                    'routes' => [
                          'index' => ['name'=>'admin.widgets.index','params'=>null,'display'=>'List'],
                          'pdf' => ['name'=>'admin.widgets.show','params'=>$guid,'display'=>'PDF Preview'],
                          'edit' => ['name'=>'admin.widgets.edit','params'=>$obj->slug,'display'=>'Edit'],
                          'delete' => ['name'=>'admin.widgets.destroy','params'=>$obj->id,'display'=>'Delete','extra_classes'=>$extraClasses['delete']],
                        ],
                ],
        ]);
    } // show()

    public function create(Request $request)
    {
        return \View::make('admin.widgets.create', [
            'pageheading' => 'Create New Widget',
            'minor_nav'=>[
                'routes' => [
                     'index' => ['name'=>'admin.widgets.index','params'=>null,'display'=>'List'],
                ],
            ],
        ]);

    } // create()


    public function edit($slug)
    {
        $obj = Widget::where('slug',$slug)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find Widget with slug '.$slug);
        }

        return \View::make('admin.widgets.edit', [
            'obj' => $obj,
            'pageheading' => 'Edit Widget',
            'indexroute' => 'admin.widgets.index',
            'minor_nav'=>[
                'routes' => [
                     'index' => ['name'=>'admin.widgets.index','params'=>null,'display'=>'List'],
                     'show' => ['name'=>'admin.widgets.show','params'=>$obj->slug,'display'=>'Show'],
                ],
            ],
            //$data['agency'] = $agency = $obj->agency;
            //$data['filters'] = ['agency_id'=>$obj->id];
        ]);

    } // edit()


    public function store(Request $request)
    {
        $this->validate($request, Widget::$vrules);

        try {

            $attrs = $request->all();
    
            $obj = \DB::transaction(function () use ($attrs) {
                $obj = Widget::create($attrs);
                return $obj;
            });

        } catch (\Exception $e) {
            throw $e;
            //$messages = [$e->getMessage()];
            //$response = ['is_ok' => 0,'messages' => $messages];
        }

        return \Redirect::route('admin.widgets.show', [$obj->slug]);

    } // store()

    public function update(Request $request, $pkid)
    {
        $this->validate($request, Widget::$vrules);

        try {
    
            $obj = Widget::find($pkid);
            if ( empty($obj) ) {
                throw new ModelNotFoundException('Could not find Widget with pkid '.$pkid);
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

        return \Redirect::route('admin.widgets.show', [$obj->slug]);

    } // update()

/*

    public function destroy($pkid)
    {
        $obj = \App\Models\Pole::find($pkid);
        if ( empty($obj) ) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Could not find Pole with pkid '.$pkid);
        }

        $isDeletable = $obj->isDeletable();
        if ( !$isDeletable ) {
            if ( $obj->is_default ) {
                throw new \Exception('Can not delete default pole -- slug: '.$obj->slug);
            }
            if ( count($obj->permittemplates) > 0 ) {
                throw new \Exception('Can not delete pole with attached permit templates -- slug: '.$obj->slug);
            }
            if ( count($obj->users) > 0 ) {
                throw new \Exception('Can not delete pole with attached users -- slug: '.$obj->slug);
            }
            throw new \Exception('Can not delete Pole, unknown reason -- slug: '.$obj->slug);
        }

        $obj->delete();

        return \Redirect::route('admin.poles.index')->with('message', 'Pole with slug='.$obj->slug.' deleted');
    } // destroy()
     */
}

            //throw new ModelNotFoundException('Could not find object with slug '.$request->agency_slug);

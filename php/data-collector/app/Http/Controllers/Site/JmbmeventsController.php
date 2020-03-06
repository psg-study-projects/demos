<?php
namespace App\Http\Controllers\Site;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\SiteController;
use App\Libs\DatatableUtils\TableContainer;
use App\Models\Jmbmevent;
use App\Models\JmbmeventTypeEnum;
use App\Models\Mediafile;
use App\Models\MediafileTypeEnum;

class JmbmeventsController extends SiteController
{
    public function __construct()
    {
        parent::__construct();
        $this->_assetMgr->registerJsLibs([ 
            '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', // %FIXME: move these to index() only?
            '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js ',
            'js/vendor/tinymce/js/tinymce/tinymce.min.js',
            //'js/app/site/libs/cmsForm.js',
        ]);
        $this->_assetMgr->registerJsInlines([
            '/js/app/common/inline/initDataTables.js',
            '/js/app/common/inline/initDeleteUtils.js'
        ]);
        $this->_assetMgr->registerCssInlines([
            '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
            '/css/app/main/styles.css',
        ]);
    }

    // eg: /cms?filters[by_department]=docketing
    public function index(Request $request)
    {
        $dtC_jmbmevents = new TableContainer( 'jmbmevents', '\App\Models\Jmbmevent', [
            [
                'colName'=>'guid', // colName -> column name in DB, not displayed
                'op'=>'link_to_route',
                'route'=>'site.jmbmvents.show',
                'resourceIdCol'=>'guid', // column value to use for route param if applicable
            ],
            ['colName'=>'etitle'],
            ['colName'=>'etype'],
            ['colName'=>'created_at'],
        ]);

        if (!\Request::ajax()) {

            $this->_php2jsVars['datatables'] = [
                'jmbmevents'=>[
                    'filters'=>$request->input('filters', []),
                    'columns'=>$dtC_jmbmevents->columns(), // needed by JS at time of page render (before AJAX)
                    'meta'=>$dtC_jmbmevents->meta(), // can filters go here?
                ],
            ];
            \View::share('g_php2jsVars',$this->_php2jsVars);

            return \View::make('site.cms.index', [
                'filters' => $request->input('filters', []),
                //'jmbmevents'=>$jmbmevents, //$jmbmevents = Jmbmevent::get();
            ]);

        } else {
            $paging = ['offset'=>$request->input('start',0),'length'=>$request->input('length',10)];
            $filters =  $request->input('filters',[]);
            $search = $request->input('search',[]);

            $sorting = [
                'value' => $request->columns[$request->order[0]['column']]['name'],
                'direction' => $request->order[0]['dir']
            ];

            $recordsTotal    = Jmbmevent::collector()->getCount();
            $recordsFiltered = Jmbmevent::collector()->getCount($filters,$search);
            $records         = Jmbmevent::collector()->getList($filters,$search,$paging,$sorting);

            // Apply any post-adaptors (decorators)...  
            $options = $request->input('options',[]); 
            $records = TableContainer::renderColumnVals( $records, $request->meta);

            $response = ['records'=>$records, 'recordsTotal'=>$recordsTotal,'recordsFiltered'=>$recordsFiltered];

            return \Response::json($response);
        }
    }

    public function show(Request $request, $guid)
    {
        $obj = Jmbmevent::where('guid',$guid)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find Jmbmevent record with guid = '.$guid);
        }

        switch ($obj->etype) {
            case JmbmeventTypeEnum::IMAGE:
            case JmbmeventTypeEnum::TEXT:
            case JmbmeventTypeEnum::BIRTHDAYS_ETC:
                return \View::make('site.jmbmevents.show', ['obj'=>$obj]);
            default:
                throw new \Exception('unsupported JMBM event etype : '.$obj->etype);
        }
    }

    // to edit CMS settings (path, etc)
    public function edit(Request $request, $guid)
    {
        $obj = Jmbmevent::where('guid',$guid)->first();
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find Jmbmevent record with guid = '.$guid);
        }

        switch ($obj->etype) {
            case JmbmeventTypeEnum::TEXT:
                return \View::make('site.jmbmevents.edit', ['obj'=>$obj]);
            case JmbmeventTypeEnum::IMAGE:
            case JmbmeventTypeEnum::BIRTHDAYS_ETC:
            default:
                throw new \Exception('Edit not supported for JMBM event etype : '.$obj->etype);
        }
    }

    public function create(Request $request)
    {
        return \View::make('site.jmbmevents.create');
    }

    public function store(Request $request)
    {
        // %NOTE: Definitely disallow '.' as it is used to delimit ckey!
        $request->validate([
            'etype' => 'required',
        ]);
        // %FIXME: ckey.1 is required if ckey.2 is filled (else just ignore 1, fail 'gracefully')

        $obj = Jmbmevent::create([
            'etitle'=>$request->etitle ?? null,
            'etype'=>$request->etype,
            'ebody'=>$request->input('ebody',null),
        ]);

        if ( \Request::ajax() ) {
            return \Response::json([ 'obj' => $obj ]);
        } else {
            return \Redirect::route('site.jmbmevents.manage');
            //return \Redirect::route('site.jmbmevents.show', $obj->guid);
        }
    }

    public function update(Request $request, $pkid)
    {
        $sessionUser = \Auth::user();

        $obj = Jmbmevent::find($pkid);
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find jmbmevent with pkid '.$pkid);
        }

        $obj->fill($request->all());
        $obj->save();

        //$tmpA = explode( '.', $obj->ckey);
        if ( \Request::ajax() ) {
            return \Response::json([ 'obj' => $obj ]);
        } else {
            $msg = 'Saved...';
            return back()->with('message',$msg);
            //return \Redirect::route('site.jmbmevents.manage');
            //return \Redirect::route('site.jmbmevents.show', $obj->guid);
        }
    }

    public function upload(Request $request)
    {
        $sessionUser = \Auth::user();
        $this->_assetMgr->registerJsLibs([
            '//code.jquery.com/ui/1.12.1/jquery-ui.min.js', // needs 'widget'
            '/js/vendor/plupload/plupload.full.min.js',
            '/js/vendor/plupload/jquery.ui.plupload.min.js',
        ]);
        $this->_assetMgr->registerCssInlines([
            '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/smoothness/jquery-ui.min.css',
            '/css/vendor/plupload/jquery.ui.plupload.css',
        ]);
        return \View::make('site.jmbmevents.upload'); // %TODO move to sub-folder events/upload.blade.php
    }

    public function manage(Request $request)
    {
        $sessionUser = \Auth::user();

        $data = [];
        $events = Jmbmevent::where('is_visible',1)->orderBy('priority','DESC')->get();

        $data['events'] = $events;
        return \View::make('site.jmbmevents.manage', $data); // %TODO move to sub-folder events/manage.blade.php
    }

    public function destroy(Request $request, $pkid)
    {
        $sessionUser = \Auth::user();

        $obj = Jmbmevent::find($pkid);
        if ( empty($obj) ) {
            throw new ModelNotFoundException('Could not find jmbmevent with pkid '.$pkid);
        }
        $msg = 'There was a problem...'; // default

        if ( !$obj->isDeletable() ) {
            $msg = 'Delete not permitted on JMBM Event with guid: '.$obj->renderField('guid');
            return back()->with('message',$msg);
        } else {
            $msg = DB::transaction(function () use (&$obj) {
                // First remove any mediafiles associated with this CMS page
                $existingMF = Mediafile::where('mftype',MediafileTypeEnum::EVENT_IMG)
                                        ->where('resource_type','jmbmevents')
                                        ->where('resource_id',$obj->id)
                                        ->get();
                foreach ($existingMF as $mf) {
                    $mf->deleteMF();
                }
                $obj->delete(); // Then, remove the [jmbmevents] record
                $msg = 'JMBM Event page with guid '.$obj->renderField('guid').' successfully deleted';
                return $msg;
            });
            return redirect(route('site.jmbmevents.manage'))->with('message',$msg);
        }
    }


}

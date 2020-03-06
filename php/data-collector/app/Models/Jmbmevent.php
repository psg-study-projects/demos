<?php 
namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Libs\Collector\Collectable;
use App\Libs\Collector\CollectableTraits;
use App\Libs\Utils\SmartEnum;
use App\Libs\Utils;

class Jmbmevent extends BaseModel implements Guidable, Collectable, Deletable, Selectable, Formmodelable
{
    use CollectableTraits;

    protected $guarded = ['id','guid','slug','created_at','updated_at'];

    // Default Validation Rules: may be overridden in controller...but NOTE these are used in renderFormLabel() and isFieldRequired() !
    public static $vrules = [
            'etitle' => 'required',
            'etype' => 'required',
            //'account_id' => 'required|integer',
        ];

    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function mediafiles() {
        return $this->morphMany('App\Models\Mediafile','resource');
    }
    public function getImage() {
        if ( (JmbmeventTypeEnum::IMAGE === $this->etype) && ($this->mediafiles()->count() > 0) ) {
            return $this->mediafiles[0];
        } else {
            return null;
        }
    }

    //--------------------------------------------
    // Accessors/Mutators
    //--------------------------------------------

    public function getEattrsAttribute($value) {
        return empty($value) ? [] : json_decode($value,true);
    }
    public function setEattrsAttribute($value) {
        $this->attributes['cattrs'] = json_encode($value);
    }

    public function getMetaAttribute($value) {
        return empty($value) ? [] : json_decode($value,true);
    }
    public function setMetaAttribute($value) {
        $this->attributes['meta'] = json_encode($value);
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Deletable Interface ---

    public function isDeletable() : bool
    {
        switch ($this->etype) {
            case JmbmeventTypeEnum::BIRTHDAYS_ETC:
                return false;
            default:
                return true;
        }
    }

    // %%% --- Implement Collectable Interface ---

    // queryApplyFilter
    public static function filterQuery(&$query,$filters)
    {
        if ( !empty($filters['etitle']) ) {
            $query->where('etitle',$filters['etitle']);
        }
        if ( !empty($filters['etype']) ) {
            $query->where('etype',$filters['etype']);
        }
        return $query;
    }


    // queryApplySearch
    public static function searchQuery(&$query,$search)
    {
        if ( empty($search) || ( is_array($search) && empty($search['value']) ) ) {
            return $query; // no search string, ignore
        }
        $searchStr = is_array($search) ? $search['value'] : $search; // latter is simple string
        $query->where( function ($q1) use($searchStr) {
            $q1->orWhere('guid', 'like', $searchStr.'%');
            $q1->orWhere('etitle', 'like', '%'.$searchStr.'%');
        });
        return $query;

    } // applySearch()

    public static function getSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array
    {
        $records = self::all(); // %TODO : add filter capability
        $options = [];
        if ($includeBlank) {
            $options[''] = '';
        }
        foreach ($records as $i => $r) {
            $options[$r->{$keyField}] = $r->etitle;
        }
        return $options;
    }

    // %%% --- Overrides in Model Traits (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = trim($key);
        switch ($key) {
            case 'etitle':
                $key = 'Event Title';
                break;
            case 'etype':
                $key = 'Event Type';
                break;
            case 'ebody':
                $key = 'Body Text';
                break;
            default:
                $key =  parent::_renderFieldKey($key);
        }
        return $key;
    }

    public function renderField(string $field) : ?string
    {
        $key = trim($field);
        switch ($key) {
            case 'ebody':
                return json_encode($this->ebody);
            case 'meta':
                return json_encode($this->meta);
            case 'etype':
                return JmbmeventTypeEnum::render($this->etype);
            default:
                return parent::renderField($field);
        }
    }


    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return $this->etitle ?? $this->renderField('guid');
    }

    // %%% --- Implement Formmodelable Interface (%TODO) ---

    public static function renderFormmodelFieldArray(array $_data=null, array $_cols=null) : array 
    {
        $a = [];
        $columns = empty($_cols) ? \Schema::getColumnListing( self::getTableName() ) : $_cols;
        foreach ($columns as $c) {
            // set default attributes, may be overridden
            $attrs = [
                'col_name' => $c,
                'label' => self::_renderFieldKey($c),
                'is_required' => array_key_exists($c,self::$vrules) && ( false !== strpos(self::$vrules[$c], 'required') ),
            ];
            switch ($c) {
                case 'id':
                case 'guid':
                case 'slug':
                case 'meta':
                case 'created_at':
                case 'updated_at':
                    break; // ignore
                    /*
                case 'priority': // part of meta json-encoded field
                    $a['priority'] =  \View::make('formmodels.bs4._meta', $attrs)->render();
                    break;
                     */
                default:
                    $a[$c] =  \View::make('formmodels.bs4._text', $attrs)->render();
                    break;
            }
        }

//dd('here', $a, $columns);
        return $a;
    }

    // %%% --- Other ---

    public function renderHtml() : string 
    {
        switch ($this->etype) {

            case JmbmeventTypeEnum::IMAGE:
                return \View::make('site.jmbmevents._show_image', ['obj'=>$this])->render();

            case JmbmeventTypeEnum::TEXT:
                return \View::make('site.jmbmevents._show_text', ['obj'=>$this])->render();

            case JmbmeventTypeEnum::BIRTHDAYS_ETC:

                $DAYS_IN_WINDOW = 30;
                $nowYday = date('z')+1;
                $users = User::where('estatus', EmployeeStatusEnum::ACTIVE)->get();

                // birthdays: filter
                $birthdays = $users->filter( function($u,$k) use($nowYday, $DAYS_IN_WINDOW) {
                    if ( empty($u->birthday) ) {
                        return false;
                    }
                    if ( false !== ($ts = strtotime($u->birthday)) ) {
                        $php_date = getdate($ts);
                        $iterYday = $php_date['yday'] + 1; // ordinal # of the day in the year (1 to 366)
                        // %TODO: corner case: current date is Dec 20, it will "wrap"
                        return ($iterYday > $nowYday) && ($iterYday <= $nowYday+$DAYS_IN_WINDOW) ? true : false;
                    }
                });

                // birthdays: sort
                $birthdays = $birthdays->sortBy('birthday'); // assumes all years are '1901'

                // anniversaires: filter
                $anniversaries = $users->filter( function($u,$k) use($nowYday, $DAYS_IN_WINDOW) {
                    if ( empty($u->start_date) ) {
                        return false;
                    }
                    if ( false !== ($ts = strtotime($u->start_date)) ) {
                        $php_date = getdate($ts);
                        $iterYday = $php_date['yday'] + 1; // ordinal # of the day in the year (1 to 366)
                        // %TODO: corner case: current date is Dec 20, it will "wrap"
                        return ($iterYday > $nowYday) && ($iterYday <= $nowYday+$DAYS_IN_WINDOW) ? true : false;
                    }
                });

                // anniversaires: sort
                $anniversaries = $anniversaries->sort(function ($a, $b) {
                    $phpDateA = getdate( strtotime($a->start_date) );
                    $phpDateB = getdate( strtotime($b->start_date) );
                    $iterYdayA = $phpDateA['yday']; // ordinal # of the day in the year (0 to 365)
                    $iterYdayB = $phpDateB['yday'];
                    return $iterYdayA - $iterYdayB; // ASC
                });

                return \View::make('site.jmbmevents._show_birthdays', [
                    'obj' => $this,
                    'birthdays' => $birthdays, // ->sortBy('birthday'),
                    'anniversaries' => $anniversaries, // ->sortBy('start_date'),
                ])->render();

            default:
                throw new \Exception('unsupported Jmbm Event etype : '.$this->etype);
        }
    }
}

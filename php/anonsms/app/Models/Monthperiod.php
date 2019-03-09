<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;

class Monthperiod extends BaseModel implements Guidable, Sluggable, Collectable, Selectable
{
    use CollectableTraits;

    protected $guarded = ['id','guid','created_at','updated_at'];

    public static $vrules = [
            'period_year' => 'required|date_format:Y',
            'month_number' => 'required|date_format:m',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ];


    //--------------------------------------------
    // Relations
    //--------------------------------------------

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Sluggable Interface ---

    public function sluggableFields() : array
    {
        return ['period_year','month_number'];
    }

    // --- Implement Selectable Interface ---

    public static function getSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array
    {

        if ( count($filters) > 0 ) {
            $query = self::query();
            if ( !empty($filters['start_monthperiod_id']) ) {
                $query->where('id','>=', $filters['start_monthperiod_id']);
            }
            if ( !empty($filters['end_monthperiod_id']) ) {
                $query->where('id','<=', $filters['end_monthperiod_id']);
            }
            if ( !empty($filters['from_current_month']) ) {
                $ds = Carbon::now()->startOfMonth()->toDateString();
                //dd( $ds );
                $query->whereDate('start_date','>=', $ds);
            }
            $records = $query->get();

        } else {
            $records = self::all(); // %TODO : add filter capability
        }

        $options = [];
        if ($includeBlank) {
            $options[''] = '';
        }
        foreach ($records as $i => $r) {
            $options[$r->{$keyField}] = $r->renderName();
        }
        return $options;
    }

    // %%% --- Implement Collectable Interface ---

    // queryApplyFilter
    public static function filterQuery(&$query,$filters)
    {
        return $query;
    }


    // queryApplySearch
    public static function searchQuery(&$query,$search)
    {
        if ( empty($search) || ( is_array($search) && !array_key_exists('value',$search) ) ) {
            return $query; // no search string, ignore
        }
        $searchStr = is_array($search) ? $search['value'] : $search; // latter is simple string
        $query->where( function ($q1) use($searchStr) {
            $q1->orWhere('guid', 'like', $searchStr.'%');
            $q1->orWhere('slug', 'like', $searchStr.'%');
            $q1->orWhere('period_year', 'like', $searchStr.'%');
            $q1->orWhere('month_number', 'like', $searchStr.'%');
        });
        return $query;

    } // applySearch()


    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return Carbon::parse($this->start_date)->format('M') . ' '.$this->period_year;
    }

    // %%% --- Overrides in Model Traits (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = trim($key);
        switch ($key) {
            case 'period_year':
                $key = 'Year';
                break;
            case 'month_number':
                $key = 'Month';
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
            case 'month_number':
                return Carbon::parse($this->start_date)->format('M');
            default:
                return parent::renderField($field);
        }
    }

    // %%% --- Additional Functions ---

    public function getNext() 
    {
        $c = Carbon::createFromDate($this->period_year, $this->month_number, 1);
        $c->addMonthsNoOverflow(1);
        $record = self::where('period_year',$c->year)
                      ->where('month_number',$c->month)
                      ->firstOrFail();
        return $record;
    }
    public function getPrevious() 
    {
        $c = Carbon::createFromDate($this->period_year, $this->month_number, 1);
        $c->addMonthsNoOverflow(-1);
        $record = self::where('period_year',$c->year)
                      ->where('month_number',$c->month)
                      ->firstOrFail();
        return $record;
    }
}

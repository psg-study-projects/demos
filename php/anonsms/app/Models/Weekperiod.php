<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;

class Weekperiod extends BaseModel implements Guidable, Sluggable, Collectable
{
    use CollectableTraits;

    protected $guarded = ['id','guid','created_at','updated_at'];

    public static $vrules = [
            'period_year' => 'required|date_format:Y',
            'week_number' => 'required|integer|min:1|max:53',
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
        return ['period_year','week_number'];
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
            $q1->orWhere('week_number', 'like', $searchStr.'%');
        });
        return $query;

    } // applySearch()


    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return $this->slug;
    }

}

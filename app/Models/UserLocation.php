<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/**
 * Class UserLocation
 * @property int id
 * @property int user_id
 * @property string country
 * @property string city
 * @property string location
 * @property string neighborhood
 * @property string address
 * @property string latitude
 * @property string longitude
 * @property string resource_url
 *
 * @package App\Models
 * @method static findOrFail(int $userID)
 * @method static where(string $string, int $userLocationID)
 */
class UserLocation extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_locations';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'country',
        'city',
        'location',
        'neighborhood',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['resource_url'];

    /**
     * @return UrlGenerator|Application|string
     */
    public function getResourceUrlAttribute()
    {
        return url('/admin/user-location/'.$this->getKey());
    }
}

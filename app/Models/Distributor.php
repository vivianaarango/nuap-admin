<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Distributor
 * @property int id
 * @property int user_id
 * @property string business_name
 * @property string city
 * @property string location
 * @property string neighborhood
 * @property string address
 * @property string latitude
 * @property string longitude
 * @property string commission
 * @property string type
 * @property string name_legal_representative
 * @property string cc_legal_representative
 * @property string contact_legal_representative
 *
 * @package App\Models
 */
class Distributor extends Model
{
    /**
     * @var string
     */
    protected $table = 'distributors';

    /**
     * @var string[]
     */
    protected $fillable = [
        'business_name',
        'city',
        'location',
        'neighborhood',
        'address',
        'latitude',
        'longitude',
        'commission',
        'type',
        'name_legal_representative',
        'cc_legal_representative',
        'contact_legal_representative'
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
        return url('/admin/distributors/'.$this->getKey());
    }
}

<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Commerce
 * @property int id
 * @property int user_id
 * @property string business_name
 * @property string nit
 * @property string second_phone
 * @property string commission
 * @property string type
 * @property string name_legal_representative
 * @property string cc_legal_representative
 * @property string contact_legal_representative
 * @property string url_rut
 * @property string url_commerce_room
 * @property string url_cc_legal_representative
 * @property string url_establishment_image
 * @property string url_interior_image
 * @property string url_contract
 * @property string resource_url
 *
 * @package App\Models
 * @method static create(array $data)
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $commerceID)
 */
class Commerce extends Model
{
    /**
     * @var string
     */
    protected $table = 'commerces';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'nit',
        'second_phone',
        'commission',
        'type',
        'name_legal_representative',
        'cc_legal_representative',
        'contact_legal_representative',
        'url_rut',
        'url_commerce_room',
        'url_cc_legal_representative',
        'url_establishment_image',
        'url_interior_image',
        'url_contract'
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
        return url('/admin/commerce/'.$this->getKey());
    }
}

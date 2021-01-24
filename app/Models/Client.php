<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class Client
 * @property int id
 * @property int user_id
 * @property string name
 * @property string last_name
 * @property string identity_number
 * @property string resource_url
 *
 * @package App\Models
 * @method static where(string $string, int $userID)
 * @method static findOrFail(int $clientID)
 * @method static create(array $data)
 * @method static select(string $string, string $string1)
 */
class Client extends Model
{
    /**
     * @var string
     */
    protected $table = 'clients';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'identity_number'
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
        return url('/admin/client/'.$this->getKey());
    }
}

<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class AdminUser
 * @property int id
 * @property int user_id
 * @property string name
 * @property string last_name
 * @property string identity_number
 * @property string image_url
 *
 * @package App\Models
 * @method static create(array $data)
 * @method static where(string $string, int $userID)
 */
class AdminUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'admin_users';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'identity_number',
        'image_url'
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
        return url('/admin/admin-users/'.$this->getKey());
    }
}

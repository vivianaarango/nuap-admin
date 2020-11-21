<?php
namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class User
 * @property int id
 * @property string email
 * @property string phone
 * @property boolean phone_validated
 * @property string phone_validated_date
 * @property string password
 * @property boolean status
 * @property string role
 * @property string last_logged_in
 *
 * @property mixed first
 * @package App\Models
 * @method static where(string $string, string $email)
 * @method static create(array $sanitized)
 * @method static findOrFail(int $userID)
 */
class User extends Model
{
    /**
     * @var boolean
     */
    public const STATUS_ACTIVE = true;

    /**
     * @var boolean
     */
    public const STATUS_INACTIVE = false;

    /**
     * @var string
     */
    public const ADMIN_ROLE = 'Administrador';

    /**
     * @var string
     */
    public const DISTRIBUTOR_ROLE = 'Distribuidor';

    /**
     * @var string
     */
    public const COMMERCE_ROLE = 'Comercio';

    /**
     * @var string
     */
    public const USER_ROLE = 'Usuario';

    /**
     * @var boolean
     */
    public const PHONE_VALIDATED = true;

    /**
     * @var boolean
     */
    public const PHONE_NOT_VALIDATED = false;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string[]
     */
    protected $fillable = [
        'email',
        'phone',
        'phone_validated',
        'phone_validated_date',
        'password',
        'status',
        'role',
        'last_logged_in',
    ];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $dates = [
        'last_logged_in',
        'phone_validated_date',
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
        return url('/admin/users/'.$this->getKey());
    }
}
